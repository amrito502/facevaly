<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition(): array
    {
        return [
            'user_id'       => User::factory(),
            'name'          => fake()->name(),
            'phone'         => fake()->phoneNumber(),
            'full_address'  => fake()->address(),
            'city'          => fake()->city(),
            'area'          => fake()->streetName(),
            'postal_code'   => fake()->postcode(),
            'type'          => fake()->randomElement(['shipping', 'billing']),
            'is_default'    => false,
        ];
    }
}