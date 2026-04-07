<?php

namespace Database\Factories;

use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttributeValueFactory extends Factory
{
    protected $model = AttributeValue::class;

    public function definition(): array
    {
        $value = fake()->unique()->randomElement([
            'Red',
            'Blue',
            'Black',
            'White',
            'Small',
            'Medium',
            'Large',
            '64GB',
            '128GB',
            '256GB'
        ]);

        return [
            'attribute_id' => Attribute::factory(),
            'value'        => $value,
            'slug'         => Str::slug($value) . '-' . fake()->unique()->numberBetween(1, 999),
            'meta'         => fake()->optional()->safeColorName(),
            'is_active'    => true,
        ];
    }
}