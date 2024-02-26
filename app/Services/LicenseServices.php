<?php


namespace App\Services;

use App\Repositories\HistoryPaymentRepository;
use App\Repositories\LicenseRepository;
use App\Repositories\LocationRepository;
use Carbon\Carbon;

class LicenseServices {

    protected $carbonService;
    protected $licenseRepository;
    protected $historyPaymentRepository;

    public function __construct() {
        $this->carbonService = new CarbonServices();
        $this->licenseRepository = new LicenseRepository();
        $this->historyPaymentRepository = new HistoryPaymentRepository();
    }


    // ========================================
    // Проверка оплаты в этом месяце
    public function checkPayLocation($license){
        $now_date = Carbon::now();
        $month = isset($license->month) ?
            $license->month :
            ( isset($license['month']) ? $license['month'] : null );

        if(!is_null($month)){
            // месяц текущий ?
            if(
                $this->carbonService->getCarbonYearMonth($month) ==
                $this->carbonService->getCarbonYearMonth($now_date)
            ){ return true; }
        }

    return false;
    }

    // ========================================
    // Подсчет стоимости оплаты исходя из:
    // цены локации, кол-во аттракционов, кол-во дней до конца месяца
    public function getPaymentCost($location){
        // цена локации в месяц
        $price = $location->price;
        // количество дней до начало следущего месяца
        $count_days = $this->carbonService->countDayForNextMonth() + 1;
        // количество аттракционов на локации
        $count_attractions = $location->attractions->count();
        // сумма оплаты
        // цена суток * кол-во до конца мес * кол-во аттракционов
        $payment_cost = (($price / Carbon::now()->daysInMonth) * $count_days) * $count_attractions;

        return $payment_cost;
    }

    // ========================================
    // внести данные оплаты лицензии
    public function payPayment($admin_id, $location_id, $request = null){

        $totalPayment = is_null($request) ? $request : $request->transactions[0]->amount->total;

        $license = $this->licenseRepository->updateWhere(
            [
                'user_id' => $admin_id,
                'location_id' => $location_id
            ],
            [
                'month' => Carbon::now(),
                'request' => is_null($request) ? $request : json_encode(json_decode($request)),
                'total_payment' => $totalPayment
            ]);
        return $license;
    }

    // ========================================
    // сохранить в истории данные оплаты лицензии
    public function historyPayPayment($admin_id, $location_id, $request = null){
        $history = $this->historyPaymentRepository->create([
            'user_id'=>$admin_id,
            'location_id'=>$location_id,
            'request'=>is_null($request) ? $request : json_decode($request),
        ]);
        return $history;
    }

}
