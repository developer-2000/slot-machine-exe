<?php

namespace App\Http\Requests\Session;

use App\Http\Requests\ApiFormRequest;


class EditSessionRequest extends ApiFormRequest {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    // отловить параметр get и передача в request
    public function all($keys = null) {
        $data = parent::all($keys);
        $data['session_id'] = $this->route('session');
        return $data;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     * правила проверки
     */
    public function rules() {
        return [
            // проверка существования такого id в таблице attractions
            'session_id' => 'required|integer|exists:sessions,id',
            'attraction_id' => 'required|integer|exists:attractions,id',
        ];
    }


}
