<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'sort_order',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_size')
                    ->withPivot('stock', 'price_adjustment')
                    ->withTimestamps();
    }
}