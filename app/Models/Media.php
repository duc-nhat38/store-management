<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Plank\Mediable\Media as Mediable;

class Media extends Mediable
{
    use HasFactory, SoftDeletes;
}
