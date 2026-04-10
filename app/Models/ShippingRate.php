<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingRate extends Model
{
    protected $fillable = [
        'location',
        'shipping_cost',
        'status',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}