<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends ProductRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        // $this->rules['file'] = ['nullable', 'image'];
        $this->rules['file'] = ['nullable'];
        $this->rules['name'] = ['required', 'string', 'unique:products,name,' . $this->product->id];

        return $this->rules;
    }
}
