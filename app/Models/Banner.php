<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image',  // Stores just filename like "banner1.jpg"
        'link',
        'order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer'
    ];

    /**
     * Get the full URL for the banner image
     * For CPanel: Images stored in public/banners/ directory
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('banners/' . $this->image);
        }
        return null;
    }

    /**
     * Get only active banners ordered by their display order
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }

    /**
     * Get the local file path for the image
     * For file operations (delete, move, etc.)
     */
    public function getImagePathAttribute()
    {
        if ($this->image) {
            return public_path('banners/' . $this->image);
        }
        return null;
    }
}