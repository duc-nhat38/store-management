<?php

namespace App\Http\Requests\Store;

use App\Enums\ProductStatus;
use App\Enums\StoreStatus;
use App\Http\Requests\ApiRequest;
use App\RepositoryInterfaces\ProductRepositoryInterface;
use Illuminate\Validation\Rule;

class CreateStoreRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $productRepository = resolve(ProductRepositoryInterface::class);
        $productIds = $productRepository->pluck('id')->toArray();

        return [
            'name' => 'bail|required|string|max:255',
            'email' => 'bail|nullable|string|email|max:255',
            'phone_number' => 'bail|nullable|string|regex:/^[0-9\-]+$/|min:8|max:20',
            'address' => 'bail|nullable|string|max:255',
            'fax' => 'bail|nullable|string|regex:/^[0-9\-]+$/|min:8|max:20',
            'operation_start_date' => 'bail|nullable|date|date_format:Y-m-d',
            'number_of_employees' => 'bail|nullable|integer|min:0|max:4294967295',
            'status' => [
                'bail',
                'required',
                'integer',
                Rule::in(StoreStatus::getValues())
            ],
            'note' => 'bail|nullable|string|max:5000',
            'products' => 'bail|nullable|array',
            'products.*.id' => [
                'bail',
                'required',
                'integer',
                Rule::in($productIds)
            ],
            'products.*.status' => [
                'bail',
                'required',
                'integer',
                Rule::in(ProductStatus::getValues())
            ],
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'products.*.id.in' => __('validation.exists'),
        ];
    }
}
