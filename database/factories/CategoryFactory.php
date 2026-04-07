<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'parent_id'  => null,
            'name'       => ucwords($name),
            'slug'       => Str::slug($name) . '-' . fake()->unique()->numberBetween(10, 9999),
            'icon'       => fake()->optional()->word(),
            'image'      => fake()->optional()->imageUrl(640, 480, 'category'),
            'is_active'  => true,
            'sort_order' => fake()->numberBetween(0, 50),
        ];
    }
}