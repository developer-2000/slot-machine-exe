<?php

namespace App\Http\Requests\Location;

use App\Http\Requests\ApiFormRequest;


class StoreLocationRequest extends ApiFormRequest {

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
            'admin_id' => 'required|integer|exists:users,id',
            'title' => 'required|string|max:255|unique:locations,title',
            'country' => 'required|json|max:255',
            'region' => 'required|json|max:255',
            'city' => 'required|json|max:255',
            'street' => 'required|json|max:255',
            'price' => 'nullable|integer|between:0,64000',
            'working_hours' => 'required|json|max:255',
        ];
    }


}
