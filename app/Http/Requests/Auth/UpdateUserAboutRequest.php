<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateUserAboutRequest extends ApiFormRequest
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
            'first_name' => 'required|min:2|string|max:20',
            'nickname' => 'required|string|min:2|max:30|unique:users,nickname,'.$id,
            'date_of_birth' => 'required|date|before:-10 years',
        ];
    }

}
