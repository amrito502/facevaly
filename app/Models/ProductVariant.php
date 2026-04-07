<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'name',
        'slug',
        'sku',
        'barcode',
        'purchase_price',
        'regular_price',
        'discounted_price',
        'wholesale_price',
        'wholesale_min_qty',
        'stock_qty',
        'weight_kg',
        'image',
        'is_default',
        'is_active',
    ];

    protected $casts = [
        'purchase_price'    => 'decimal:2',
        'regular_price'     => 'decimal:2',
        'discounted_price'  => 'decimal:2',
        'wholesale_price'   => 'decimal:2',
        'weight_kg'         => 'decimal:3',
        'wholesale_min_qty' => 'integer',
        'stock_qty'         => 'integer',
        'is_default'        => 'boolean',
        'is_active'         => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function values(): HasMany
    {
        return $this->hasMany(ProductVariantValue::class);
    }
}