<?php

namespace Database\Factories;

use App\Models\Unit;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class UnitFactory extends Factory
{
    protected $model = Unit::class;

    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Piece',
            'Kilogram',
            'Gram',
            'Liter',
            'Milliliter',
            'Box',
            'Pack',
            'Meter'
        ]);

        return [
            'name'       => $name,
            'slug'       => Str::slug($name) . '-' . fake()->unique()->numberBetween(1, 999),
            'short_name' => match ($name) {
                'Piece' => 'pc',
                'Kilogram' => 'kg',
                'Gram' => 'g',
                'Liter' => 'L',
                'Milliliter' => 'ml',
                'Box' => 'box',
                'Pack' => 'pack',
                'Meter' => 'm',
                default => strtolower(substr($name, 0, 3)),
            },
            'is_active'  => true,
        ];
    }
}