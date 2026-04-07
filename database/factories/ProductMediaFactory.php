<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductMedia;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductMediaFactory extends Factory
{
    protected $model = ProductMedia::class;

    public function definition(): array
    {
        return [
            'product_id'  => Product::factory(),
            'type'        => fake()->randomElement(['image', 'video']),
            'role'        => fake()->randomElement(['thumbnail', 'gallery']),
            'file_path'   => fake()->imageUrl(800, 800, 'products'),
            'sort_order'  => fake()->numberBetween(0, 10),
            'is_primary'  => false,
        ];
    }
}