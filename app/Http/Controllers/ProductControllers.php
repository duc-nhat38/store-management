<?php

namespace App\Http\Controllers;

use App\Helpers\Facades\JsonResponse;
use App\Http\Resources\Product\ProductCollection;
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
}
