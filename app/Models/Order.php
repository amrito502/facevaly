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

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total' => 'decimal:2',
        'delivery_date' => 'date',
        'canceled_date' => 'date',
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

    public function subOrders()
    {
        return $this->hasMany(SubOrder::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}