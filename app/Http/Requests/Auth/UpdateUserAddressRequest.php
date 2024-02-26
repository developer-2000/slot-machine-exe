<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateUserAddressRequest extends ApiFormRequest
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
            'country' => 'required|json|max:255',
            'region' => 'required|json|max:255',
            'city' => 'required|json|max:255',
            'street' => 'required|string|max:255',
        ];
    }

}
