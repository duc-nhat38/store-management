<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Plank\Mediable\Mediable;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    use HasFactory, SoftDeletes, Mediable;

    /** @var array */
    protected $fillable = [
        'owner_id',
        'name',
        'code',
        'category_id',
        'trademark_id',
        'quantity',
        'price',
        'currency',
        'origin',
        'status',
        'description'
    ];

    /** @var array */
    protected $casts = [
        'price' => 'float',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function trademark()
    {
        return $this->belongsTo(Trademark::class, 'trademark_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function stores()
    {
        return $this->belongsToMany(Store::class, 'store_product', 'product_id', 'store_id')
            ->withTimestamps()
            ->withPivot(['status']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeMyOwner(Builder $query): void
    {
        $query->whereHas('owner', function ($e) {
            $e->where('users.id', auth()->id());
        });
    }
}
