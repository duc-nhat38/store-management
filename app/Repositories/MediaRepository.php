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

    /**
     * @param array $ids
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getByIds(array $ids)
    {
        $this->newQuery();

        return $this->query->whereIn('id', $ids)->get();
    }
}
