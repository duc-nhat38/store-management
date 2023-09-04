<?php

namespace App\Repositories;

use App\RepositoryInterfaces\StoreRepositoryInterface;
use Illuminate\Http\Request;

class StoreRepository extends BaseRepository implements StoreRepositoryInterface
{
    /**
     * @return string
     */
    public function getModel()
    {
        return \App\Models\Store::class;
    }

    /**
     * @return array
     */
    public function filter()
    {
        return [
            ['name', 'like', '%', '%'],
            ['email', 'like', '%', '%'],
            ['phone_number', 'like', '%', '%'],
            ['address', 'like', '%', '%'],
            'number_of_employees_from' => ['number_of_employees', '>='],
            'number_of_employees_to' => ['number_of_employees', '<='],
            'status',
        ];
    }

    /**
     * @return array
     */
    public function sort()
    {
        return ['id', 'name', 'number_of_employees_from', 'created_at', 'updated_at'];
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function getMyStore(Request $request)
    {
        $search = $request->all();
        $this->newQuery()
            ->queryFilter($search)
            ->whereRelation($search)
            ->with($request)
            ->querySort($search);

        $this->query = $this->query->whereHas('manager', function ($e) {
            $e->where('id', auth()->id());
        });

        if ($request->limit) {
            return $this->query->paginate($request->limit);
        }

        return $this->query->get();
    }
}
