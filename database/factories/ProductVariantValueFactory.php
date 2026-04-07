<?php

namespace Database\Factories;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\ProductVariant;
use App\Models\ProductVariantValue;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductVariantValueFactory extends Factory
{
    protected $model = ProductVariantValue::class;

    public function definition(): array
    {
        return [
            'product_variant_id' => ProductVariant::factory(),
            'attribute_id'       => Attribute::factory(),
            'attribute_value_id' => AttributeValue::factory(),
        ];
    }
}