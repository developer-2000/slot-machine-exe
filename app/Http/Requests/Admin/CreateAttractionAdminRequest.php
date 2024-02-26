<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;

class CreateAttractionAdminRequest extends ApiFormRequest {

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
            'location_id' => 'required|integer|exists:locations,id',
            'title' => "nullable|string|max:255|unique:attractions,title",
        ];
    }


}
