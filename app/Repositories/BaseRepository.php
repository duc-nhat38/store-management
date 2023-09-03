<?php

namespace App\Repositories;

use App\RepositoryInterfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

abstract class BaseRepository implements BaseRepositoryInterface
{
    /** @var \Illuminate\Contracts\Foundation\Application|\Illuminate\Foundation\Application */
    protected $app;

    /** @var \Illuminate\Database\Eloquent\Model */
    protected $model;

    /** @var \Illuminate\Database\Eloquent\Builder */
    protected $query;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->app = app();
        $this->makeModel();
    }

    /**
     * @return string
     */
    abstract public function getModel();

    /**
     * @return void
     */
    public function makeModel()
    {
        if (!($this->model instanceof Model)) {
            $this->model = $this->app->make($this->getModel());
        }
    }

    /**
     * @return $this
     */
    public function newQuery()
    {
        $this->query = $this->model->newQuery();

        return $this;
    }

    /**
     * @return array
     */
    public function getFillter()
    {
        return [];
    }

    /**
     * @param mixed $value
     * @param string $column
     * @return \Illuminate\Database\Eloquent\Model|mixed
     */
    public function find($value, string $column = 'id')
    {
        $this->newQuery();

        return $this->query->where($column, $value)->first();
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function get(Request $request)
    {
        $this->newQuery();

        $query = $this->query;

        foreach ($this->getFillter() as $key => $column) {
            $fieldName = is_numeric($key) ? $column : $key;

            $query = $query->when($request->filled($fieldName), function ($q) use ($request, $fieldName, $column) {
                $q->where($column, 'like', '%' . escapeLike($request->{$fieldName}) . '%');
            });
        }

        if ($request->limit) {
            return $query->paginate($request->limit);
        }

        return $query->get();
    }
}
