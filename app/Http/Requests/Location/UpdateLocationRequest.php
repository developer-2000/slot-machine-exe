<?php

namespace App\Http\Requests\Location;

use App\Http\Requests\ApiFormRequest;


class UpdateLocationRequest extends ApiFormRequest {

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
        $data['location_id'] = $this->route('location');
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
            'location_id' => 'required|integer|exists:locations,id',
            'title' => 'required|string|max:255|unique:locations,title,'.$this->location_id,
            'country' => 'required|json|max:255',
            'region' => 'required|json|max:255',
            'city' => 'required|json|max:255',
            'street' => 'required|json|max:255',
            'price' => 'required|integer|between:0,64000',
            'working_hours' => 'required|json|max:255',
        ];
    }

//'attraction' => 'required|json',
}
