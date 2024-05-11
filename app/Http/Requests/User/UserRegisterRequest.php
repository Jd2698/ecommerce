<?php

namespace App\Http\Requests\User;

use App\Http\Requests\User\UserRequest;

class UserRegisterRequest extends UserRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return $this->rules;
    }
}
