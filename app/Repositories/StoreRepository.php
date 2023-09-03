<?php

namespace App\Repositories;

use App\RepositoryInterfaces\StoreRepositoryInterface;

class StoreRepository extends BaseRepository implements StoreRepositoryInterface
{
    /**
     * @return string
     */
    public function getModel()
    {
        return \App\Models\Store::class;
    }
}
