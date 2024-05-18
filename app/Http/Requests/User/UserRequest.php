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

        $this->rules['file'] = ['required', 'image'];
        $this->rules['role'] = ['required', 'string', 'in:client,admin'];

        return $this->rules;
    }
}
