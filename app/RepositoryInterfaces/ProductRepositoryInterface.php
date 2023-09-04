<?php

namespace App\RepositoryInterfaces;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param integer $storeId
     * @param \Illuminate\Http\Request|null $request
     * @return @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function getByStore(int $storeId, ?\Illuminate\Http\Request $request = null);
}
