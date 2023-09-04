<?php

namespace App\Http\Controllers;

use App\Helpers\Facades\JsonResponse;
use App\Http\Resources\Store\StoreCollection;
use App\RepositoryInterfaces\StoreRepositoryInterface;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * @param \App\RepositoryInterfaces\StoreRepositoryInterface $storeRepository
     */
    public function __construct(
        protected StoreRepositoryInterface $storeRepository
    ) {
        $this->middleware('pagination_limit')->only(['index']);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $stores = $this->storeRepository->getMyStore($request);

        return JsonResponse::success(StoreCollection::getResponse($stores), __('List of stores.'));
    }
}
