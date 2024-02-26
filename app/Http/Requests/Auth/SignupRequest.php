<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\ApiFormRequest;

// Регистрация
use Illuminate\Support\Carbon;

class SignupRequest extends ApiFormRequest
{
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
            'nickname' => 'required|string|min:2|max:50|unique:users,nickname',
            'email' => 'required|string|email|min:6|max:255|unique:users,email',
            'password' => 'required|min:6|string|max:255',
//            'first_name' => 'required|min:2|string|max:20',
//            'country' => 'required|json|max:255',
//            'region' => 'required|json|max:255',
//            'city' => 'required|json|max:255',
//            'street' => 'required|string|max:255',
//            'date_of_birth' => 'required|date|before:-10 years',
        ];
    }

}
