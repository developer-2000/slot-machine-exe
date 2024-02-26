<?php

namespace App\Http\Requests\Session;

use App\Http\Requests\ApiFormRequest;


class PlayerStartRequest extends ApiFormRequest {

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
            'session_id' => 'required|integer|exists:sessions,id',
            'gamer_id' => 'required|integer|exists:users,id',
            'session_token' => 'required|string|exists:sessions,session_token',
        ];
    }
}
