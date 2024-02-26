<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\ApiFormRequest;


class SetPaginateAdminRequest extends ApiFormRequest {

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
            'count' => 'required|max:50|integer'
        ];
    }


}
