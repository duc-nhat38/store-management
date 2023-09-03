<?php

namespace App\Http\Controllers;

use App\Helpers\Facades\JsonResponse;
use App\Http\Resources\Trademark\TrademarkCollection;
use App\RepositoryInterfaces\TrademarkRepositoryInterface;
use Illuminate\Http\Request;

class TrademarkController extends Controller
{
    /**
     * @param \App\RepositoryInterfaces\TrademarkRepositoryInterface $trademarkRepository
     */
    public function __construct(
        protected TrademarkRepositoryInterface $trademarkRepository
    ) {
        //
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $trademarks = $this->trademarkRepository->get($request);

        return JsonResponse::success(TrademarkCollection::getResponse($trademarks), 'List of trademarks');
    }
}
