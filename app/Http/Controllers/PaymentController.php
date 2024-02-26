<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\BaseApiController;
use \App\Http\Requests\Payment\PaymentRequest;
use App\Services\PaymentServices;
use Illuminate\Http\Request;

class PaymentController extends BaseApiController {

    protected $paymentService;

    public function __construct() {
        parent::__construct();
        $this->paymentService = new PaymentServices();
    }

    // >>>>>
    // Сбор данных для платежа и связь с сервисом
    public function payWithpaypal(PaymentRequest $request) {
        $index = $request->validated();
        $response = $this->paymentService->payWithpaypal($index);

        if (is_string($response)){
            return $this->getErrorResponse($response, 406);
        }

        return response()->json($response, 201);
    }

    // >>>>>
    // Возврат данных оплаты с сервиса
    public function getPaymentStatus(Request $request) {

        return $this->paymentService->getPaymentStatus($request);
    }
}
