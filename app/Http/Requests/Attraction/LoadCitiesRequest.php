<?php

namespace App\Http\Requests\Attraction;

use App\Http\Requests\ApiFormRequest;


class LoadCitiesRequest extends ApiFormRequest {

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
            'code' => 'required|max:9999999999|integer'
        ];
    }


}
