<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductVariantFactory extends Factory
{
    protected $model = ProductVariant::class;

    public function definition(): array
    {
        $name = fake()->randomElement([
            'Red - Large',
            'Blue - Medium',
            'Black - 128GB',
            'White - 256GB',
        ]);

        $regularPrice = fake()->numberBetween(200, 4000);

        return [
            'product_id'         => Product::factory(),
            'name'               => $name,
            'slug'               => Str::slug($name) . '-' . fake()->unique()->numberBetween(10, 9999),
            'sku'                => strtoupper(fake()->unique()->bothify('VAR-#####')),
            'barcode'            => fake()->ean13(),
            'purchase_price'     => fake()->randomFloat(2, 50, 2000),
            'regular_price'      => $regularPrice,
            'discounted_price'   => fake()->boolean(60) ? fake()->numberBetween(100, $regularPrice) : null,
            'wholesale_price'    => fake()->boolean(40) ? fake()->randomFloat(2, 100, 3000) : null,
            'wholesale_min_qty'  => fake()->boolean(40) ? fake()->numberBetween(5, 20) : null,
            'stock_qty'          => fake()->numberBetween(0, 100),
            'weight_kg'          => fake()->randomFloat(3, 0.1, 10),
            'image'              => fake()->optional()->imageUrl(600, 600, 'variant'),
            'is_default'         => false,
            'is_active'          => true,
        ];
    }
}