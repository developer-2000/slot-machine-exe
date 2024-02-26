<?php

namespace App\Http\Requests\Attraction;

use App\Http\Requests\ApiFormRequest;


class UpdateAttractionRequest extends ApiFormRequest {

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
        $data['attraction_id'] = $this->route('attraction');
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
            'attraction_id' => 'required|integer|exists:attractions,id',
            'location_id' => 'required|integer|exists:locations,id',
            'title' => "required|string|max:255|unique:attractions,title,{$this->id}",
            'activation' => 'nullable|integer|in:0,1',
        ];
    }

}
