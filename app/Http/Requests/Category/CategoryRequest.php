<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => ['required', 'string'],
        ];

        if ($this->method() == 'POST') {
            array_push($rules['name'], 'unique:categories,name');
        } else {
            array_push($rules['name'], 'unique:categories,name,' . $this->category->id);
        }

        return $rules;
    }
}
