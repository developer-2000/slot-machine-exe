<?php

namespace App\Http\Requests\User;

use App\Http\Requests\ApiFormRequest;


class AddImageUserRequest extends ApiFormRequest {

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
            'select_id' => 'required|integer',
            'column' => 'required|string|max:255|in:avatar_image,border_image,change_image,impact_image,victory_image'
        ];
    }

}
