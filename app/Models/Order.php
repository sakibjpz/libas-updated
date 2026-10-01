<?php

namespace App\Models;

use App\Models\Coupon;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    // Allow mass assignment for these fields
    protected $fillable = [
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'delivery_area',
        'items',
        'total',
        'shipping_cost',
        'status',
        'steadfast_consignment_id',
        'steadfast_tracking_code',
        'steadfast_status',
        'steadfast_response',
        'steadfast_sent_at',
        'fraud_flag',
        'fraud_score',
        'fraud_flags',
        'fraud_checked_at',
        'coupon_id',
    ];

    // Cast items JSON to array
    protected $casts = [
        'items' => 'array',
        'total' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'steadfast_response' => 'array',
        'steadfast_sent_at' => 'datetime',
        'fraud_flags' => 'array',
        'fraud_checked_at' => 'datetime',
    ];
    
    /**
     * Get the items as array (simplified)
     */
    public function getItemsAttribute($value)
    {
        return is_array($value) ? $value : json_decode($value, true);
    }
    
    /**
     * Get total items count
     */
    public function getItemsCountAttribute()
    {
        $items = $this->items;
        if (is_array($items)) {
            return count($items);
        }
        return 0;
    }
    
    /**
     * Check if order is sent to Steadfast
     */
    public function getIsSentToSteadfastAttribute(): bool
    {
        return !is_null($this->steadfast_consignment_id);
    }
    
    /**
     * Get the order items for this order (relationship)
     * Renamed from items() to avoid conflict with JSON items column
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Coupon relationship
     */
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
}