<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trademark extends Model
{
    use HasFactory, SoftDeletes;

    /** @var array */
    protected $fillable = [
        'name',
        'nation'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'trademark_id', 'id');
    }
}
