<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Plank\Mediable\Media as Mediable;

class Media extends Mediable
{
    use HasFactory, SoftDeletes;

    /** @var array */
    protected $guarded = [
        'id',
        'disk',
        'directory',
        'display_name',
        'filename',
        'extension',
        'size',
        'mime_type',
        'aggregate_type',
        'variant_name',
        'original_media_id'
    ];
}
