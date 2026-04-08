<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'coupon_id',
        'subtotal',
        'discount',
        'tax_rate_id',
        'tax',
        'shipping_rate_id',
        'shipping_cost',
        'total',
        'name',
        'phone',
        'full_address',
        'payment_method',
        'payment_status',
        'status',
        'delivery_date',
        'canceled_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function shippingRate()
    {
        return $this->belongsTo(ShippingRate::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}