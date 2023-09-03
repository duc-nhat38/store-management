<?php

namespace App\Repositories;

use App\RepositoryInterfaces\CategoryRepositoryInterface;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    /**
     * @return string
     */
    public function getModel()
    {
        return \App\Models\Category::class;
    }

    /**
     * @return array
     */
    public function getFillter()
    {
        return ['name'];
    }
}
