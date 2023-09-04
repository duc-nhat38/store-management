<?php

namespace App\Repositories;

use App\Enums\ProductStatus;
use App\RepositoryInterfaces\StoreRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

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

        $this->query = $this->query->underMyManagement();

        if ($request->limit) {
            return $this->query->paginate($request->limit);
        }

        return $this->query->get();
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    protected function getFormRequest(Request $request)
    {
        $attributes = $request->only([
            'name',
            'email',
            'phone_number',
            'address',
            'fax',
            'operation_start_date',
            'number_of_employees',
            'status',
            'note'
        ]);
        $attributes['manager_id'] = auth()->id();

        return $attributes;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Model|$this
     */
    public function create($request)
    {
        $this->newQuery();
        $attributes = $this->getFormRequest($request);
        $productPivots = [];

        if ($request->has('products')) {
            $productPivots = $this->transformProductPivot($request->get('products'));
        }

        return DB::transaction(function () use ($attributes, $productPivots) {
            $store = $this->query->create($attributes);
            throw_unless($store, \Exception::class, __('Create store failed'), Response::HTTP_INTERNAL_SERVER_ERROR);

            if (!empty($productPivots)) {
                $store->products()->sync($productPivots);
            }

            return $store;
        });
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Model|$this
     */
    public function update($request, $id)
    {
        $this->newQuery();
        $attributes = $this->getFormRequest($request);
        $productPivots = [];

        if ($request->has('products')) {
            $productPivots = $this->transformProductPivot($request->get('products'));
        }

        return DB::transaction(function () use ($id, $attributes, $productPivots) {
            $store = $this->query->findOrFail($id);
            throw_unless($store->update($attributes), \Exception::class, __('Update store failed.'), Response::HTTP_INTERNAL_SERVER_ERROR);

            if (!empty($productPivots)) {
                $store->products()->sync($productPivots);
            }

            return $store;
        });
    }

    /**
     * @param array $products
     * @return array
     */
    protected function transformProductPivot(array $products)
    {
        $productPivots = [];

        foreach ($products as $value) {
            $productPivots[$value['id']] = ['status' => $value['status'] ?? ProductStatus::AVAILABLE];
        }

        return $productPivots;
    }

    /**
     * @param int|array $id
     * @return int
     */
    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $store = $this->findOrFail($id);
            $store->products()->detach();

            return $store->delete();
        });
    }

    /**
     * @param mixed $value
     * @param string $column
     * @return \Illuminate\Database\Eloquent\Model|mixed
     */
    public function findOrFail($value, string $column = 'id')
    {
        $this->newQuery();
        $model = $this->query->where($column, $value)->underMyManagement()->first();

        throw_unless($model, \Exception::class, __('Not found.'), Response::HTTP_NOT_FOUND);

        return $model;
    }
}
