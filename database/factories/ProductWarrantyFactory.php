<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductWarranty;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductWarrantyFactory extends Factory
{
    protected $model = ProductWarranty::class;

    public function definition(): array
    {
        return [
            'product_id'     => Product::factory(),
            'type'           => fake()->randomElement(['replacement', 'service']),
            'duration'       => fake()->numberBetween(1, 24),
            'duration_unit'  => fake()->randomElement(['day', 'month', 'year']),
        ];
    }
}