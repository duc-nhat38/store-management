<?php

namespace App\Http\Controllers;

use App\Helpers\Facades\JsonResponse;
use App\Http\Requests\Store\CreateStoreRequest;
use App\Http\Requests\Store\UpdateStoreRequest;
use App\Http\Resources\Store\StoreCollection;
use App\Http\Resources\Store\StoreResource;
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

    /**
     * @param \App\Http\Requests\Store\CreateStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateStoreRequest $request)
    {
        $store = $this->storeRepository->create($request);

        return JsonResponse::success(StoreResource::make($store)->resolve(), __('Create store successfully.'));
    }

    /**
     * @param \App\Http\Requests\Store\UpdateStoreRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateStoreRequest $request, $id)
    {
        $store = $this->storeRepository->update($request, $id);

        return JsonResponse::success(StoreResource::make($store)->resolve(), __('Update store successfully.'));
    }
}
