<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Shop;
use App\Models\Unit;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);
        $regularPrice = fake()->numberBetween(200, 5000);
        $discountedPrice = fake()->boolean(60)
            ? fake()->numberBetween(100, $regularPrice)
            : null;

        return [
            'shop_id'            => Shop::factory(),
            'category_id'        => Category::factory(),
            'brand_id'           => Brand::factory(),
            'unit_id'            => Unit::factory(),

            'name'               => ucwords($name),
            'slug'               => Str::slug($name) . '-' . fake()->unique()->numberBetween(100, 99999),
            'sku'                => strtoupper(fake()->unique()->bothify('SKU-#####')),
            'barcode'            => fake()->ean13(),

            'product_type'       => fake()->randomElement(['single', 'variant']),

            'description'        => fake()->paragraphs(3, true),
            'specification'      => fake()->paragraphs(2, true),

            'purchase_price'     => fake()->randomFloat(2, 50, 2000),
            'regular_price'      => $regularPrice,
            'discounted_price'   => $discountedPrice,

            'is_retail'          => true,
            'is_wholesale'       => fake()->boolean(40),
            'wholesale_price'    => fake()->boolean(40) ? fake()->randomFloat(2, 100, 4000) : null,
            'wholesale_min_qty'  => fake()->boolean(40) ? fake()->numberBetween(5, 50) : null,

            'stock_qty'          => fake()->numberBetween(0, 200),
            'weight_kg'          => fake()->randomFloat(3, 0.1, 20),

            'warranty_type'      => fake()->randomElement(['no_warranty', 'seller_warranty']),
            'warranty_policy'    => fake()->boolean(50) ? fake()->sentence() : null,

            'tax_amount'         => fake()->randomFloat(2, 0, 15),
            'tax_type'           => fake()->randomElement(['fixed', 'percent']),

            'status'             => fake()->randomElement(['draft', 'pending', 'active', 'inactive', 'rejected']),
            'is_featured'        => fake()->boolean(20),

            'view_count'         => fake()->numberBetween(0, 5000),
            'rating_avg'         => fake()->randomFloat(2, 0, 5),
            'rating_count'       => fake()->numberBetween(0, 500),

            'published_at'       => fake()->optional()->dateTimeBetween('-3 months', 'now'),
        ];
    }
}