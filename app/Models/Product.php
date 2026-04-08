<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'shop_id',
        'category_id',
        'brand_id',
        'unit_id',
        'name',
        'slug',
        'sku',
        'barcode',
        'product_type',
        'description',
        'specification',
        'purchase_price',
        'regular_price',
        'discounted_price',
        'is_retail',
        'is_wholesale',
        'wholesale_price',
        'wholesale_min_qty',
        'stock_qty',
        'weight_kg',
        'warranty_type',
        'warranty_policy',
        'tax_amount',
        'tax_type',
        'status',
        'is_featured',
        'view_count',
        'rating_avg',
        'rating_count',
        'published_at',
    ];

    protected $casts = [
        'purchase_price'      => 'decimal:2',
        'regular_price'       => 'decimal:2',
        'discounted_price'    => 'decimal:2',
        'wholesale_price'     => 'decimal:2',
        'weight_kg'           => 'decimal:3',
        'tax_amount'          => 'decimal:2',
        'rating_avg'          => 'decimal:2',
        'is_retail'           => 'boolean',
        'is_wholesale'        => 'boolean',
        'is_featured'         => 'boolean',
        'stock_qty'           => 'integer',
        'wholesale_min_qty'   => 'integer',
        'view_count'          => 'integer',
        'rating_count'        => 'integer',
        'published_at'        => 'datetime',
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(ProductMedia::class);
    }

    public function thumbnail(): HasOne
    {
        return $this->hasOne(ProductMedia::class)->where('role', 'thumbnail');
    }

    public function warranties(): HasMany
    {
        return $this->hasMany(ProductWarranty::class);
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function carts()
{
    return $this->hasMany(Cart::class);
}
}