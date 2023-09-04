<?php

namespace App\RepositoryInterfaces;

interface StoreRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function getMyStore(\Illuminate\Http\Request $request);
}
