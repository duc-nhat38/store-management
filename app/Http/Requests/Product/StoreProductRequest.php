<?php

namespace App\Http\Requests\Product;

use App\Enums\ProductStatus;
use App\Http\Requests\ApiRequest;
use App\RepositoryInterfaces\StoreRepositoryInterface;
use Illuminate\Validation\Rule;

class StoreProductRequest extends ApiRequest
{
    /** @var string */
    protected $mediaMimes;

    /** @var string */
    protected $mediaMimetypes;

    /** @var int */
    protected $maxFileSize;

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $mediaMimes = [];
        $mediaMimetypes = [];

        foreach (['image', 'vector', 'video'] as $key) {
            array_push($mediaMimes, ...config('mediable.aggregate_types.' . $key . '.extensions'));
            array_push($mediaMimetypes, ...config('mediable.aggregate_types.' . $key . '.mime_types'));
        }

        $this->mediaMimes = implode(',', $mediaMimes);
        $this->mediaMimetypes = implode(',', $mediaMimetypes);
        $this->maxFileSize = config('mediable.max_size') / 1000;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $storeRepository = resolve(StoreRepositoryInterface::class);
        $storeIds = $storeRepository->getMyStore($this->duplicate()->replace([]))->pluck('id')->toArray();

        return [
            'name' => 'bail|required|string|max:255',
            'code' => [
                'bail',
                'required',
                'string',
                Rule::unique('products', 'code')->where('owner_id', $this->user()->id)->withoutTrashed(),
                'min:10',
                'max:100'
            ],
            'stores' => 'bail|nullable|array',
            'stores.*.id' => [
                'bail',
                'required',
                'integer',
                Rule::in($storeIds)
            ],
            'stores.*.status' => [
                'bail',
                'required',
                'integer',
                Rule::in(ProductStatus::getValues())
            ],
            'category_id' => 'bail|required|exists:categories,id',
            'trademark_id' => 'bail|nullable|exists:trademarks,id',
            'quantity' => 'bail|nullable|integer|min:0|max:4294967295',
            'price' => 'bail|nullable|numeric|min:0|max:99999999999999,99',
            'currency' => 'bail|nullable|string|max:100',
            'origin' => 'bail|nullable|string|max:255',
            'status' => [
                'bail',
                'required',
                'integer',
                Rule::in(ProductStatus::getValues())
            ],
            'description' => 'bail|nullable|string|max:5000',
            'thumbnail' => 'bail|nullable|file|mimes:' . $this->mediaMimes . ',' . strtoupper($this->mediaMimes) . '|mimetypes:' . $this->mediaMimetypes . '|max:' . $this->maxFileSize,
            'media' => 'bail|nullable|array|max:10',
            'media.*' => 'bail|required|file|mimes:' . $this->mediaMimes . ',' . strtoupper($this->mediaMimes) . '|mimetypes:' . $this->mediaMimetypes . '|max:' . $this->maxFileSize
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'stores.*.id.in' => __('validation.exists'),
            'media.*.mimes' => __('validation.mimetypes', ['value' => $this->mediaMimes]),
            'media.*.mimetypes' => __('validation.mimetypes', ['value' => $this->mediaMimes]),
            'media.*.max' => __('validation.max.file', ['max' => round($this->maxFileSize / 1024)]),
            'media.*.max' => __('validation.max.file', ['max' => round($this->maxFileSize / 1024)]),
        ];
    }
}
