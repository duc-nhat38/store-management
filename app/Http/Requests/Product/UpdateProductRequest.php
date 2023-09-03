<?php

namespace App\Http\Requests\Product;

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

        $rules['code'] = 'bail|required|string|unique:products,code,' . $this->product . ',id,deleted_at,NULL|min:10|max:100';
        $rules['files_delete'] = 'bail|nullable|array';

        return $rules;
    }
}
