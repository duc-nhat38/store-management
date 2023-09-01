<?php

namespace App\Repositories;

use App\RepositoryInterfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

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
}
