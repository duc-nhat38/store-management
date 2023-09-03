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
        [\App\RepositoryInterfaces\CategoryRepositoryInterface::class, \App\Repositories\CategoryRepository::class],
        [\App\RepositoryInterfaces\TrademarkRepositoryInterface::class, \App\Repositories\TrademarkRepository::class],
        [\App\RepositoryInterfaces\ProductRepositoryInterface::class, \App\Repositories\ProductRepository::class],
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
