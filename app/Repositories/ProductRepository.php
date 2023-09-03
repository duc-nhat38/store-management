<?php

namespace App\Repositories;

use App\RepositoryInterfaces\ProductRepositoryInterface;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    /**
     * @return string
     */
    public function getModel()
    {
        return \App\Models\Product::class;
    }

    /**
     * @return array
     */
    public function filter()
    {
        return [
            ['code', 'like', '%', '%'],
            ['name', 'like', '%', '%'],
            'quantity_from' => ['quantity', '>='],
            'quantity_to' => ['quantity', '<='],
            'price_from' => ['price', '>='],
            'price_to' => ['price', '<='],
            'currency',
            ['origin', 'like', '%', '%'],
            'status'
        ];
    }

    /**
     * @param array $search
     * @return $this
     */
    public function whereRelation(array $search)
    {
        foreach (['category_id' => 'category', 'trademark_id' => 'trademark'] as $column => $relation) {
            $value = $search[$column] ?? null;

            $this->query = $this->query->when(isNotEmptyStringOrNull($value), function ($q) use ($relation, $value) {
                $q->whereHas($relation, function ($e) use ($value) {
                    $e->where('id', $value);
                });
            });
        }

        return $this;
    }
}
