<?php

namespace App\Http\Requests\Admin\Auth;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;

class SignUpAdminRequest extends ApiFormRequest {

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
            'first_name' => 'required|min:2|string|max:30',
            'surname' => 'required|string|min:2|max:30',
            'middlename' => 'required|string|min:2|max:30',
            'company' => 'required|string|min:3|max:150',
            'email' => 'required|email|min:8|max:150|unique:users,email',
            'tell' => 'required|string|min:8|max:50',
        ];
    }


}
