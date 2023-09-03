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
    public function filter()
    {
        return [];
    }

    /**
     * @param array $search
     * @return $this
     */
    public function queryFilter(array $search)
    {
        foreach ($this->filter() as $key => $column) {
            $column = (array) $column;
            $columnName = $column[0] ?? '';
            $fieldName = is_numeric($key) ? $columnName : $key;

            $this->query = $this->query
                ->when(isNotEmptyStringOrNull($search[$fieldName] ?? null), function ($q) use ($search, $column, $fieldName, $columnName) {
                    if (($column[1] ?? null) === 'like') {
                        $q->where($columnName, 'like', ($column[2] ?? '') . escapeLike($search[$fieldName]) . ($column[3] ?? ''));
                    } else {
                        $q->where($columnName, ($column[1] ?? '='), $search[$fieldName]);
                    }
                });
        }

        return $this;
    }

    /**
     * @param array $search
     * @return $this
     */
    public function whereRelation(array $search)
    {
        return $this;
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
        $search = $request->all();
        $this->newQuery()->queryFilter($search)->whereRelation($search);

        if ($request->limit) {
            return $this->query->paginate($request->limit);
        }

        return $this->query->get();
    }

    /**
     * @param mixed $attributes
     * @return \Illuminate\Database\Eloquent\Model|$this
     */
    public function create($attributes)
    {
        $this->newQuery();

        return $this->query->create($attributes);
    }

    /**
     * @param string $column
     * @param string|null $key
     * @return \Illuminate\Support\Collection
     */
    public function pluck(string $column, ?string $key = null)
    {
        $this->newQuery();

        return $this->query->pluck($column, $key);
    }
}
