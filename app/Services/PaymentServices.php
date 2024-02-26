<?php
namespace App\Services;

use App\Repositories\LocationRepository;
use Carbon\Carbon;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Rest\ApiContext;
use PayPal\Api\PaymentExecution;
use \Illuminate\Support\Facades\URL;
use \Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class PaymentServices {

    protected $_api_context;
    protected $licenseService;
    protected $locationRepository;
    protected $carbonService;
    protected $paypal_conf;

    public function __construct() {
        $this->licenseService = new LicenseServices();
        $this->locationRepository = new LocationRepository();
        $this->carbonService = new CarbonServices();
        /** PayPal api context **/
        $this->paypal_conf = config('game.paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
                $this->paypal_conf['client_id'],
                $this->paypal_conf['secret'])
        );
        $this->_api_context->setConfig($this->paypal_conf['settings']);
    }


    // Сбор данных для платежа и связь с сервисом
    // ========================================
    public function payWithPaypal($index){

        // Локация с аттракционами и лицензией
        $location = $this->locationRepository->oneLocationActiveAttractionsLicense(
            [ 'id' => $index['location_id'], 'user_id' => $index['admin_id'] ]
        );

        // Проверка существования аттракционов у локации
        if(!count($location->activeAttractions)){
            return __('paypal.6');
        }
        // Проверка оплаты в этом месяце
        if($this->licenseService->checkPayLocation($location->license)){
            return __('paypal.7');
        }

        /** Создать масив info оплачиваемых аттракционов и общую цену оплаты **/
        $arr = $this->getBuyItems($location);
        $item_list = $arr[0];
        $total_payment = $arr[1];

        /** Общая стоимость **/
        $amount = new Amount();
        $amount->setCurrency('USD') ->setTotal($total_payment);

        /** Укажите обратный URL **/
        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL::route('status'))
            ->setCancelUrl(URL::route('status'));

        /** Транзакция **/
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription("Location '{$location->title}' - license payment");

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));

        /** Связь с сервисом **/
        $redirect_url = $this->serviceConnection($payment);

        // ошибка
        if (is_string($redirect_url[0])){
            return __('paypal.2');
        }

        /** добавляем ID платежа в сессию **/
        Session::put('paypal_payment', [
            'payment_id' => $payment->getId(),
            'location_id' => $index['location_id'],
            'admin_id' => $index['admin_id'],
        ]);

        /** редиректим в paypal **/
        return ['message'=>['link'=>$redirect_url[1]],'status'=>'success'];
//        return Redirect::away($redirect_url[1]);
    }


    // Возврат данных оплаты с сервиса
    // ========================================
    public function getPaymentStatus($request){

        $payment_id = null;
        $location_id = null;
        $admin_id = null;

        /** Получаем ID платежа, локации, админа **/
        if (Session::has('paypal_payment')) {
            $session = Session::get('paypal_payment');
            $payment_id = $session['payment_id'];
            $location_id = $session['location_id'];
            $admin_id = $session['admin_id'];
        }
        else{
            return __('paypal.3');
        }

        /** Очищаем ID платежа **/
        Session::forget('paypal_payment');

        /** не существует **/
        if (empty($request->PayerID) || empty($request->token)) {
            return __('paypal.4');
        }

        $payment = Payment::get($payment_id, $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId($request->PayerID);

        /** Выполняем платёж **/
        $result = $payment->execute($execution, $this->_api_context);

        if ($result->getState() == 'approved') {

            // внести данные оплаты лицензии
            $license = $this->licenseService->payPayment(
                $admin_id, $location_id, $result
            );
            // сохранить в истории данные оплаты лицензии
            $history = $this->licenseService->historyPayPayment(
                $admin_id, $location_id, $result
            );

            return redirect()->route('admin');
        }

        return __('paypal.5');
    }



    // PRIVATE
    // >>> Создать масив info оплачиваемых аттракционов и общую цену оплаты
    private function getBuyItems($location) {

        $item_list = new ItemList();

        $arrList = [];
        // цена локации в месяц
        $price = $location->price;
        // количество дней до начало следущего месяца
        $count_days = $this->carbonService->countDayForNextMonth() + 1;
        // количество аттракционов на локации
        $count_attractions = $location->activeAttractions->count();
        // цена локации до конца месяца
        // цена суток * кол-во до конца мес * кол-во аттракционов
        $payment_location = round((($price / Carbon::now()->daysInMonth) * $count_days));

        foreach ($location->activeAttractions as $key => $attraction){
            $item = new Item();

            $item->setName($attraction['title'])
                ->setCurrency('USD')
                ->setQuantity(1)
                ->setPrice($payment_location);

            array_push($arrList, $item);
        }

        return [$item_list->setItems($arrList), ($payment_location * $count_attractions)];
    }

    // >>> Связь с сервисом
    private function serviceConnection($payment){
        try {
            $payment->create($this->_api_context);
        } catch (PayPalConnectionException $ex) {
            return [ __('paypal.1')];
        }

        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                return [ true, $link->getHref() ];
            }
        }
    }

}
