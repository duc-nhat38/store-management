<?php

namespace App\Repositories;

use App\RepositoryInterfaces\TrademarkRepositoryInterface;

class TrademarkRepository extends BaseRepository implements TrademarkRepositoryInterface
{
    /**
     * @return string
     */
    public function getModel()
    {
        return \App\Models\Trademark::class;
    }

    /**
     * @return array
     */
    public function filter()
    {
        return [
            ['name', 'like', '%', '%'],
            ['nation', 'like', '%', '%']
        ];
    }
}
