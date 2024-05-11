<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    protected $rules = [
        'name' => ['required', 'string'],
        'last_name' => ['required', 'string'],
        'email' => ['required', 'email', 'unique:users,email'],
        'password' => ['confirmed', 'string', 'min:8', 'required'],
    ];

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if ($this->method() == 'PUT') {
            $this->rules['email'] = ['required', 'email', 'unique:users,email,' . $this->user->id];
            $this->rules['password'] = ['nullable', 'confirmed', 'string', 'min:8'];
        }

        $this->rules['role'] = ['required', 'string', 'in:user,admin,librarian'];

        return $this->rules;
    }
}
