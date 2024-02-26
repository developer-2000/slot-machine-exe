<?php

namespace App\Http\Requests\Admin\Language;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;


class ChangeLanguageRequest extends ApiFormRequest {

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
            'language' => 'required|string|min:2|max:2',
        ];
    }


}
