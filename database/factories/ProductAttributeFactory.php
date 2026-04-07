<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\ProductAttribute;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductAttributeFactory extends Factory
{
    protected $model = ProductAttribute::class;

    public function definition(): array
    {
        return [
            'product_id'         => Product::factory(),
            'attribute_id'       => Attribute::factory(),
            'attribute_value_id' => AttributeValue::factory(),
            'value_text'         => fake()->optional()->word(),
        ];
    }
}