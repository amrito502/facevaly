<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $electronics = Category::create([
            'name' => 'Electronics',
            'slug' => Str::slug('Electronics'),
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $fashion = Category::create([
            'name' => 'Fashion',
            'slug' => Str::slug('Fashion'),
            'is_active' => true,
            'sort_order' => 2,
        ]);

        $groceries = Category::create([
            'name' => 'Groceries',
            'slug' => Str::slug('Groceries'),
            'is_active' => true,
            'sort_order' => 3,
        ]);

        Category::create([
            'parent_id' => $electronics->id,
            'name' => 'Mobiles',
            'slug' => Str::slug('Mobiles'),
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Category::create([
            'parent_id' => $electronics->id,
            'name' => 'Laptops',
            'slug' => Str::slug('Laptops'),
            'is_active' => true,
            'sort_order' => 2,
        ]);

        Category::create([
            'parent_id' => $fashion->id,
            'name' => 'Men Fashion',
            'slug' => Str::slug('Men Fashion'),
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Category::create([
            'parent_id' => $fashion->id,
            'name' => 'Women Fashion',
            'slug' => Str::slug('Women Fashion'),
            'is_active' => true,
            'sort_order' => 2,
        ]);

        Category::factory()->count(5)->create();
    }
}