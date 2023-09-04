<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use HasFactory, SoftDeletes;

    /** @var array */
    protected $fillable = [
        'name',
        'manager_id',
        'email',
        'phone_number',
        'address',
        'fax',
        'operation_start_date',
        'number_of_employees',
        'status',
        'note'
    ];

    /**
     * Get the user that manager the Store
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'store_product', 'store_id', 'product_id')
            ->withPivot('status')
            ->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function availableProducts()
    {
        return $this->products()
            ->wherePivot('status', 1)
            ->where('products.status', 1);
    }
}
