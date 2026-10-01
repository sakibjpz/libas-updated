<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_order_amount',
        'max_uses',
        'uses',
        'valid_from',
        'valid_until',
        'active',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('active', true)
            ->where(function ($q) {
                $q->whereNull('valid_from')
                  ->orWhere('valid_from', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('valid_until')
                  ->orWhere('valid_until', '>=', now());
            });
    }

    public function isValid()
    {
        if (!$this->active) {
            return false;
        }

        if ($this->max_uses && $this->uses >= $this->max_uses) {
            return false;
        }

        if ($this->valid_from && $this->valid_from > now()) {
            return false;
        }

        if ($this->valid_until && $this->valid_until < now()) {
            return false;
        }

        return true;
    }

    public function calculateDiscount($orderAmount)
    {
        if ($this->min_order_amount && $orderAmount < $this->min_order_amount) {
            return 0;
        }

        if ($this->type === 'percentage') {
            return $orderAmount * ($this->value / 100);
        }

        return $this->value;
    }
}
