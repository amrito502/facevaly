<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function brands()
{
    return $this->hasMany(\App\Models\Brand::class);
}

public function products()
{
    return $this->hasMany(\App\Models\Product::class);
}
}