<?php

namespace App\Http\Requests\Product;

use Illuminate\Validation\Rule;

class UpdateProductRequest extends StoreProductRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = parent::rules();

        $rules['code'] = [
            'bail',
            'required',
            'string',
            Rule::unique('products', 'code')->ignore($this->product)->where('owner_id', $this->user()->id)->withoutTrashed(),
            'min:10',
            'max:100'
        ];
        $rules['files_delete'] = 'bail|nullable|array';

        return $rules;
    }
}
