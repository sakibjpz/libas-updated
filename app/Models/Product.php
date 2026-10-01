<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'landing_theme',
        'category_id',  // New foreign key
        'image',
        'gallery',
        'youtube_url',
        'price',
        'original_price',
        'discount',
        'description',
        'brand',
        'stock',
    ];

    protected $casts = [
        'gallery' => 'array',
    ];

    protected static function booted(): void
    {
        static::saving(function (Product $product) {
            if (blank($product->slug)) {
                $base = \Illuminate\Support\Str::slug($product->name) ?: 'product';
                $slug = $base;
                $i = 2;
                while (static::where('slug', $slug)->where('id', '!=', $product->id ?? 0)->exists()) {
                    $slug = $base . '-' . $i++;
                }
                $product->slug = $slug;
            }
        });
    }

    /**
     * Relationship to Category
     */
    public function categoryRelation(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Get category attribute for easy access
     */
    public function getCategoryAttribute()
    {
        return $this->categoryRelation;
    }

    /**
     * Get category name (for backward compatibility)
     */
    public function getCategoryNameAttribute()
    {
        return $this->categoryRelation ? $this->categoryRelation->name : $this->getOriginal('category');
    }

    /**
     * Get full URL for product image
     * Now looks directly in the /products folder where images are actually saved
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('products-images/' . $this->image);
        }
        return asset('images/placeholder.png');
    }

    /**
     * Get local file path for deleting/updating image
     * Updated to match the new upload location
     */
    public function getImagePathAttribute()
    {
        if ($this->image) {
            // Fixed: Now checking in the correct location where images are saved
            return public_path('products-images/' . $this->image);
        }
        return null;
    }

    /**
     * Get full URLs for all gallery images
     */
    public function getGalleryUrlsAttribute(): array
    {
        return collect($this->gallery ?? [])
            ->map(fn($file) => asset('products-images/' . $file))
            ->all();
    }

    /**
     * Extract a YouTube embed URL from watch/shorts/youtu.be links
     */
    public function getYoutubeEmbedUrlAttribute(): ?string
    {
        if (!$this->youtube_url) {
            return null;
        }
        if (preg_match('/(?:youtube\.com\/(?:watch\?[^#]*v=|embed\/|shorts\/)|youtu\.be\/)([A-Za-z0-9_-]{6,20})/', $this->youtube_url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }
        return null;
    }

    /**
     * Get sizes for this product
     */
    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'product_size')
                    ->withPivot('stock', 'price_adjustment')
                    ->withTimestamps();
    }

    /**
     * Get calculated discount percentage from original_price and price
     * Always calculates the real percentage based on actual price difference
     */
    public function getDiscountAttribute($value)
    {
        // Calculate real discount percentage from original_price and price
        if ($this->original_price && $this->original_price > $this->price) {
            return round((($this->original_price - $this->price) / $this->original_price) * 100);
        }

        return 0;
    }

    /**
     * Get colors for this product
     */
    public function colors()
    {
        return $this->belongsToMany(Color::class, 'product_color')
                    ->withPivot('stock', 'price_adjustment')
                    ->withTimestamps();
    }

    /**
     * Get reviews for this product
     */
    public function reviews()
    {
        return $this->hasMany(ProductReview::class)->where('approved', true);
    }

    /**
     * Get average rating for this product
     */
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    /**
     * Get review count for this product
     */
    public function getReviewCountAttribute()
    {
        return $this->reviews()->count();
    }

    /**
     * Get related products based on category
     */
    public function getRelatedProducts($limit = 4)
    {
        return Product::where('category_id', $this->category_id)
            ->where('id', '!=', $this->id)
            ->where('stock', '>', 0)
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }
}