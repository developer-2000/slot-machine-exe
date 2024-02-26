<?php

namespace App\Http\Requests\Payment;

use App\Http\Requests\ApiFormRequest;


class PaymentRequest extends ApiFormRequest {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     * правила проверки
     */
    public function rules() {
        return [
            'location_id' => 'required|integer|exists:locations,id',
            'admin_id' => 'required|integer|exists:users,id',
        ];
    }


}
