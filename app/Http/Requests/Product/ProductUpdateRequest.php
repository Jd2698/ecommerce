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
        $this->rules['file'] = ['nullable', 'image'];
        $this->rules['name'] = ['required', 'string', 'unique:products,name,' . $this->product->id];

        if (!$this->hasFile('file')) {
            $this->rules['file'] = ['nullable'];
        }

        return $this->rules;
    }
}
