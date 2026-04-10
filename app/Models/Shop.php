<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $fillable = [
        'seller_id',
        'shop_name',
        'slug',
        'website_url',
        'address',
        'shop_email',
        'shop_phone',
        'logo',
        'banner',
        'description',
        'verification_status',
        'status',
        'commission_rate',
        'rating_avg',
        'rating_count',
        'is_featured',
    ];

    protected $casts = [
        'commission_rate' => 'decimal:2',
        'rating_avg' => 'decimal:2',
        'is_featured' => 'boolean',
    ];
        public function brands()
    {
        return $this->hasMany(\App\Models\Brand::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function subOrders()
    {
        return $this->hasMany(SubOrder::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}