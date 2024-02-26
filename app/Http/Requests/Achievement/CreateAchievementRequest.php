<?php

namespace App\Http\Requests\Achievement;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;

class CreateAchievementRequest extends ApiFormRequest {

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
            'achievement_id' => 'required|integer|exists:achievements,id',
            'user_id' => 'required|integer|exists:users,id',
            'attraction_id' => 'required|integer|exists:attractions,id',
            'data' => 'required|json',
        ];
    }


}
