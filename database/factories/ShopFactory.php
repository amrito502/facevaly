<?php

namespace Database\Factories;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShopFactory extends Factory
{
    protected $model = Shop::class;

    public function definition(): array
    {
        $shopName = fake()->company() . ' Shop';

        return [
            'seller_id' => User::query()->inRandomOrder()->value('id') ?? User::factory(),
            'shop_name' => $shopName,
            'slug' => Str::slug($shopName) . '-' . fake()->unique()->numberBetween(100, 9999),
            'website_url' => fake()->optional()->url(),
            'address' => fake()->address(),
            'shop_email' => fake()->safeEmail(),
            'shop_phone' => fake()->phoneNumber(),
            'logo' => fake()->optional()->imageUrl(300, 300, 'business'),
            'banner' => fake()->optional()->imageUrl(1200, 400, 'business'),
            'description' => fake()->paragraph(),
            'verification_status' => fake()->randomElement(['pending', 'approved', 'rejected']),
            'status' => fake()->randomElement(['draft', 'active', 'suspended', 'closed']),
            'commission_rate' => fake()->randomFloat(2, 0, 25),
            'rating_avg' => fake()->randomFloat(2, 0, 5),
            'rating_count' => fake()->numberBetween(0, 500),
            'is_featured' => fake()->boolean(20),
        ];
    }
}