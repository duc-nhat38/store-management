<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{

    /**
    * define your repositories here
    */
    protected $repositories = [
        [\App\RepositoryInterfaces\UserRepositoryInterface::class, \App\Repositories\UserRepository::class],
        [\App\RepositoryInterfaces\CategoryRepositoryInterface::class, \App\Repositories\CategoryRepository::class]
    ];

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
