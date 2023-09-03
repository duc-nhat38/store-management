<?php

namespace App\Http\Controllers;

use App\Helpers\Facades\JsonResponse;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Resources\Product\ProductCollection;
use App\Http\Resources\Product\ProductResource;
use App\RepositoryInterfaces\ProductRepositoryInterface;
use Illuminate\Http\Request;

class ProductControllers extends Controller
{
    /**
     * @param \App\RepositoryInterfaces\ProductRepositoryInterface $productRepository
     */
    public function __construct(
        protected ProductRepositoryInterface $productRepository
    ) {
        $this->middleware('pagination_limit')->only(['index']);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $products = $this->productRepository->get($request);

        return JsonResponse::success(ProductCollection::getResponse($products), __('List of products.'));
    }

    /**
     * @param \App\Http\Requests\Product\StoreProductRequest $request
     * @return \App\Helpers\Facades\JsonResponse
     */
    public function store(StoreProductRequest $request)
    {
        $product = $this->productRepository->create($request);
        $product->load('category', 'trademark')->loadMedia();

        return JsonResponse::success(ProductResource::make($product)->resolve(), __('Create product successfully.'));
    }
}
