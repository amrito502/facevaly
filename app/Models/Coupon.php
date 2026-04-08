<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'type',
        'value',
        'cart_value',
        'expiry_date',
        'status',
        'usage_limit',
        'used_count',
    ];

    protected $casts = [
        'expiry_date' => 'date',
    ];

    public function isValidForAmount($amount): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        if ($this->expiry_date && $this->expiry_date->isPast()) {
            return false;
        }

        if ($amount < $this->cart_value) {
            return false;
        }

        if (!is_null($this->usage_limit) && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    public function calculateDiscount($amount): float
    {
        if ($this->type === 'fixed') {
            return min((float) $this->value, (float) $amount);
        }

        return round(((float) $amount * (float) $this->value) / 100, 2);
    }
}