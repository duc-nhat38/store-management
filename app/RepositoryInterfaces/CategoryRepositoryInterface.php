<?php

namespace App\RepositoryInterfaces;

interface CategoryRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function get(\Illuminate\Http\Request $request);
}
