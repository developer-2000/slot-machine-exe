<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateDataRequest extends ApiFormRequest
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

        $id = Auth::user()->id;

        return [
            'first_name' => 'required|min:2|string|max:30',
            'nickname' => 'required|string|min:2|max:30|unique:users,nickname,'.$id,
            'country' => 'required|json|max:255',
            'region' => 'required|json|max:255',
            'city' => 'required|json|max:255',
            'street' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:-10 years',
            // 'statistic' => 'required|string',
            // 'middle_name' => 'nullable|string|min:2|max:30',
            // 'surname' => 'nullable|string|min:2|max:30',
            // 'company' => 'nullable|string|min:2|max:100',
            // 'tel' => 'nullable|string|min:8|max:50',
        ];
    }

}
