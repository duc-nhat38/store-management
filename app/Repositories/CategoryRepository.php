<?php

namespace App\Repositories;

use App\RepositoryInterfaces\CategoryRepositoryInterface;
use Illuminate\Http\Request;

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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function get(Request $request)
    {
        $this->newQuery();

        $query = $this->query
        ->when($request->filled('name'), function ($q) use ($request) {
            $q->where('name', 'like', '%' . escapeLike($request->name) . '%');
        });

        if ($request->limit) {
            return $query->paginate($request->limit);
        }

        return $query->get();
    }
}
