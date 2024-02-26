<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;

class AttractionAdminRequest extends ApiFormRequest {

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
            // может быть пустым , цифры
            'filter' => [ 'nullable',
                'regex:/^[0-9]*$/u'
            ],
            'sort' => [
                'nullable',
                'string',
                'in:id,location_id,activation',
            ],
            'operator' => [
                'nullable',
                'string',
                'in:desc,asc',
            ],
        ];
    }


}
