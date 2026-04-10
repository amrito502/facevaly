<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'sub_order_id',
        'product_id',
        'shop_id',
        'seller_id',
        'seller_price',
        'sale_price',
        'quantity',
        'line_seller_total',
        'line_sale_total',
        'markup_amount',
        'commission_rate',
        'commission_amount',
        'seller_payable',
        'admin_earning',
        'options',
        'rstatus',
    ];

    protected $casts = [
        'seller_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'line_seller_total' => 'decimal:2',
        'line_sale_total' => 'decimal:2',
        'markup_amount' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'seller_payable' => 'decimal:2',
        'admin_earning' => 'decimal:2',
        'rstatus' => 'boolean',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function subOrder()
    {
        return $this->belongsTo(SubOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}