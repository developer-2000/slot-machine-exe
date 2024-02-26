<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;


class LicenseAdminRequest extends ApiFormRequest {

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

//'required|regex:/[^,:{}\\[\\]0-9.\\-+Eaeflnr-u \\n\\r\\t]/'

    public function rules() {
        return [
            // может быть пустым , цифры , строки с пробелами
            'filter' => [ 'nullable',
                'regex:/^[0-9]*$|^[a-zA-Zа-яёА-ЯЁ0-9_@+()_\- ]*$/u'
            ],
            // может быть пустым , json
            'add_filter' => 'nullable|json',
            'sort' => [
                'nullable',
                'string',
                'in:total_payment,data_last_payment',
            ],
            'operator' => [
                'nullable',
                'string',
                'in:desc,asc',
            ],
        ];
    }


}
