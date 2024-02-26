<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;


class ClientsAdminRequest extends ApiFormRequest {

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
            // может быть пустым , цифры или строки с пробелами
            'filter' => [ 'nullable',
                'regex:/^[0-9]*$|^[a-zA-Zа-яёА-ЯЁ0-9_ ]*$/u'
            ],
            'sort' => [
                'nullable',
                'string',
                'in:first_name,surname,middlename,activation',
            ],
            'operator' => [
                'nullable',
                'string',
                'in:desc,asc',
            ],
        ];
    }


}
