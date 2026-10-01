<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'hex_code',
        'sort_order',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_color')
                    ->withPivot('stock', 'price_adjustment')
                    ->withTimestamps();
    }
}