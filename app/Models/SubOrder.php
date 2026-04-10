<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubOrder extends Model
{
    protected $fillable = [
        'order_id',
        'shop_id',
        'seller_id',
        'sub_order_number',
        'subtotal',
        'discount',
        'tax',
        'shipping_cost',
        'total',
        'commission_rate',
        'commission_amount',
        'markup_amount',
        'gateway_charge',
        'seller_payable',
        'admin_earning',
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
        'commission_rate' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'markup_amount' => 'decimal:2',
        'gateway_charge' => 'decimal:2',
        'seller_payable' => 'decimal:2',
        'admin_earning' => 'decimal:2',
        'delivery_date' => 'date',
        'canceled_date' => 'date',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}