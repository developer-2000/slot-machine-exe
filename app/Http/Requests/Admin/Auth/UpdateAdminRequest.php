<?php
namespace App\Http\Requests\Admin\Auth;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;

class UpdateAdminRequest extends ApiFormRequest {

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
            'user_id' => 'required|integer|exists:users,id',
            'first_name' => 'required|string|min:2|max:50',
            'surname' => 'required|string|min:2|max:50',
            'middlename' => 'required|string|min:2|max:50',
            'company' => 'required|string|min:2|max:50',
            'email' => 'required|email|min:8|max:150|unique:users,email,'.$this->user_id,
            'tell' => 'required|string|min:2|max:50',
        ];
    }

}
