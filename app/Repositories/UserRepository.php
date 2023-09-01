<?php

namespace App\Repositories;

use App\RepositoryInterfaces\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * @return string
     */
    public function getModel()
    {
        return \App\Models\User::class;
    }
}
