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
        'seller_price',
        'sale_price',
        'markup_rate',
        'markup_amount',
        'commission_rate',
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
        'is_price_approved',
        'view_count',
        'rating_avg',
        'rating_count',
        'published_at',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'seller_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'markup_rate' => 'decimal:2',
        'markup_amount' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'regular_price' => 'decimal:2',
        'discounted_price' => 'decimal:2',
        'wholesale_price' => 'decimal:2',
        'weight_kg' => 'decimal:3',
        'tax_amount' => 'decimal:2',
        'rating_avg' => 'decimal:2',
        'is_retail' => 'boolean',
        'is_wholesale' => 'boolean',
        'is_featured' => 'boolean',
        'is_price_approved' => 'boolean',
        'published_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

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
        return $this->hasMany(ProductMedia::class)
            ->orderByDesc('is_primary')
            ->orderBy('sort_order');
    }

    public function thumbnail(): HasOne
    {
        return $this->hasOne(ProductMedia::class)
            ->where('role', 'thumbnail');
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

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors (Computed Fields)
    |--------------------------------------------------------------------------
    */

    public function getResolvedCommissionRateAttribute(): float
    {
        if (!is_null($this->commission_rate)) {
            return (float) $this->commission_rate;
        }

        if (!is_null($this->category?->commission_rate)) {
            return (float) $this->category->commission_rate;
        }

        if (!is_null($this->shop?->commission_rate)) {
            return (float) $this->shop->commission_rate;
        }

        return (float) (\App\Models\Setting::query()->value('default_commission_rate') ?? 5);
    }

    public function getEffectiveSalePriceAttribute(): float
    {
        return (float) ($this->sale_price ?? 0);
    }

    public function getEffectiveSellerPriceAttribute(): float
    {
        return (float) ($this->seller_price ?? 0);
    }
}