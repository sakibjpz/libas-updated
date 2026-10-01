<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'image', 'parent_id', 'description', 'meta_keywords'];

    public function getImageUrlAttribute(): ?string
    {
        if ($this->image) {
            return asset('categories/' . $this->image);
        }
        return null;
    }

    /**
     * Get the local file path for the image
     * For file operations (delete, move, etc.)
     */
    public function getImagePathAttribute(): ?string
    {
        if ($this->image) {
            return public_path('categories/' . $this->image);
        }
        return null;
    }

    /**
     * Get all products for this category
     */
    public function products(): HasMany
    {
        // Change from 'category' to 'category_id'
        return $this->hasMany(Product::class, 'category_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('name');
    }

    /**
     * Category IDs including this category's direct children —
     * used so a parent category page shows subcategory products too.
     */
    public function idsWithChildren(): array
    {
        // Tolerate the parent_id column missing (migration not yet run on prod)
        if (!\Illuminate\Support\Facades\Schema::hasColumn('categories', 'parent_id')) {
            return [$this->id];
        }
        return $this->children()->pluck('id')->push($this->id)->all();
    }

    public function coverProduct(): HasOne
    {
        return $this->hasOne(Product::class, 'category_id')->latestOfMany('id');
    }
}
