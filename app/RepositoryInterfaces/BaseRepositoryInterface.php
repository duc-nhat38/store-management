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
     * @param array $search
     * @return $this
     */
    public function whereRelation(array $search);

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
}
