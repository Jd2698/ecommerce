<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    protected $rules = [
        'name' => ['required', 'string', 'unique:products,name'],
        'stock' => ['required', 'numeric'],
        'description' => ['required', 'string'],
        'price' => ['required', 'numeric'],
        'category_id' => ['required', 'exists:categories,id'],
        'file' => ['required', 'image']
    ];

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return $this->rules;
    }
}
