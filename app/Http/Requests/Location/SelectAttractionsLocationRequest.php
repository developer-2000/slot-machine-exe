<?php

namespace App\Http\Requests\Location;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Support\Facades\Auth;

class SelectAttractionsLocationRequest extends ApiFormRequest
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
            'location_id' => 'required|integer|exists:locations,id',
        ];
    }

}
