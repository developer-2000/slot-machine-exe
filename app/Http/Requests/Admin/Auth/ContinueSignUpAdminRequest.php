<?php
namespace App\Http\Requests\Admin\Auth;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;

class ContinueSignUpAdminRequest extends ApiFormRequest {

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
            'email' => 'required|email|min:8|max:150',
            'code' => 'required|string||max:50',
            'password' => 'required|min:6|string|max:255',
            'password_confirmation' => 'required|same:password'
        ];
    }


}
