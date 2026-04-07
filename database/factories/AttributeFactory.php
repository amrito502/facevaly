<?php

namespace Database\Factories;

use App\Models\Attribute;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttributeFactory extends Factory
{
    protected $model = Attribute::class;

    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Color',
            'Size',
            'Storage',
            'RAM',
            'Material',
            'Weight',
            'Model'
        ]);

        return [
            'name'          => $name,
            'slug'          => Str::slug($name) . '-' . fake()->unique()->numberBetween(1, 999),
            'type'          => fake()->randomElement(['text', 'select', 'multiselect', 'color', 'number']),
            'is_filterable' => fake()->boolean(70),
            'is_variation'  => fake()->boolean(60),
            'is_required'   => fake()->boolean(40),
            'is_active'     => true,
        ];
    }
}