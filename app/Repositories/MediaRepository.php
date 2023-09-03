<?php

namespace App\Repositories;

use App\RepositoryInterfaces\MediaRepositoryInterface;

class MediaRepository extends BaseRepository implements MediaRepositoryInterface
{
    /**
     * @return string
     */
    public function getModel()
    {
        return \App\Models\Media::class;
    }
}
