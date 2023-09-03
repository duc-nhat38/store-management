<?php

namespace App\RepositoryInterfaces;

interface BaseRepositoryInterface
{
    /**
     * @return string
     */
    public function getModel();

    /**
     * @return void
     */
    public function makeModel();

    /**
     * @return $this
     */
    public function newQuery();

    /**
     * @return array
     */
    public function filter();

    /**
     * @param array $search
     * @return $this
     */
    public function queryFilter(array $search);

    /**
     * @return array
     */
    public function sort();

    /**
     * @param array $sort
     * @return $this
     */
    public function querySort(array $sort);

    /**
     * @param array $search
     * @return $this
     */
    public function whereRelation(array $search);

    /**
     * @param \Illuminate\Http\Request|null $search
     * @return $this
     */
    public function with($request = null);

    /**
     * @param mixed $value
     * @param string $column
     * @return \Illuminate\Database\Eloquent\Model|mixed
     */
    public function find($value, string $column = 'id');

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function get(\Illuminate\Http\Request $request);

    /**
     * @param mixed $attributes
     * @return \Illuminate\Database\Eloquent\Model|$this
     */
    public function create($attributes);

    /**
     * @param string $column
     * @param string|null $key
     * @return \Illuminate\Support\Collection
     */
    public function pluck(string $column, ?string $key = null);
}
