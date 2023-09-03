<?php

namespace App\RepositoryInterfaces;

interface MediaRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param array $ids
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getByIds(array $ids);
}
