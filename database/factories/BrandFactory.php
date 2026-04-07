<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Shop;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class BrandFactory extends Factory
{
    protected $model = Brand::class;

    public function definition(): array
    {
        $name = fake()->unique()->company();

        return [
            'shop_id'   => Shop::factory(),
            'name'      => $name,
            'slug'      => Str::slug($name) . '-' . fake()->unique()->numberBetween(10, 9999),
            'logo'      => fake()->optional()->imageUrl(300, 300, 'brand'),
            'is_active' => true,
        ];
    }
}