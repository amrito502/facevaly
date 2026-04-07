<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'is_filterable',
        'is_variation',
        'is_required',
        'is_active',
    ];

    protected $casts = [
        'is_filterable' => 'boolean',
        'is_variation'  => 'boolean',
        'is_required'   => 'boolean',
        'is_active'     => 'boolean',
    ];

    public function values(): HasMany
    {
        return $this->hasMany(AttributeValue::class);
    }

    public function productAttributes(): HasMany
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function productVariantValues(): HasMany
    {
        return $this->hasMany(ProductVariantValue::class);
    }
}