<?php

namespace App\Http\Requests\User;

use App\Http\Requests\User\UserRequest;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends UserRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $this->rules['email'] = ['required', 'email', 'unique:users,email,' . $this->user->id];
        $this->rules['password'] = ['nullable', 'confirmed', 'string', 'min:8'];
        $this->rules['file'] = ['nullable', 'image'];

        if (!$this->hasFile('file')) {
            $this->rules['file'] = ['nullable'];
        }

        return $this->rules;
    }
}
