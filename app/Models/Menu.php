<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'url',
        'category_id',
        'type',
        'parent_id',
        'order',
        'status'
    ];

    /**
     * Linked product category (dynamic link target)
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get parent menu
     */
    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    /**
     * Get child menus
     */
    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('order', 'asc');
    }

/**
 * Get URL for menu item
 */
public function getUrl()
{
    // Explicit URL wins (e.g. /, /products, /contact)
    if ($this->url) {
        return $this->url;
    }

    // Linked category resolves dynamically to its current slug
    if ($this->category_id && $this->category) {
        return route('products.index') . '?category=' . urlencode($this->category->slug);
    }

    // If this menu has children, return "#" for dropdown
    if ($this->children->count() > 0) {
        return '#';
    }

    // Otherwise return category link
    return route('products.index') . '?category=' . urlencode($this->slug);
}

/**
 * Get products for this menu based on category/slug matching
 */
public function getProducts()
{
    // If menu slug matches product category, return those products
    return \App\Models\Product::where('category', 'like', '%' . $this->slug . '%')
        ->orWhere('category', 'like', '%' . $this->title . '%')
        ->get();
}



}