<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'shop_id',
        'seller_id',
        'price',
        'quantity',
        'line_total',
        'options',
        'rstatus',
    ];

    protected $casts = [
        'options' => 'array',
        'rstatus' => 'boolean',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
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