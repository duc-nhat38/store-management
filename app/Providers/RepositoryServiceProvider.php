<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{

    /**
    * define your repositories here
    */
    protected $repositories = [];

    /**
     * @return void
     */
    public function register(): void
    {
        foreach ($this->repositories as $repository) {
            $this->app->bind($repository[0], $repository[1]);
        }
    }
}
