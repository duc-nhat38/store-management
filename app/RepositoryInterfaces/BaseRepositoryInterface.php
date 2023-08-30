<?php

namespace App\Http\RepositoryInterfaces;

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
}
