<?php

namespace App\Http\Requests\Session;

use App\Http\Requests\ApiFormRequest;


class ChangeModeSessionRequest extends ApiFormRequest {

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
            'mode_id' => 'required|integer',
            'session_id' => 'required|integer|exists:sessions,id',
            'attraction_id' => 'required|integer|exists:attractions,id',
            'admin_id' => 'required|integer|exists:users,id',
            'session_token' => 'required|string|exists:sessions,session_token',
            'player_max_count' => 'required|integer',
        ];
    }


}
