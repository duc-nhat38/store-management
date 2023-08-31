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
}
