<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Shop;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $shop = Shop::query()->first();

        if (!$shop) {
            return;
        }

        $brands = ['Samsung', 'Apple', 'Xiaomi', 'Nike', 'Adidas', 'Puma'];

        foreach ($brands as $brand) {
            Brand::create([
                'shop_id' => $shop->id,
                'name' => $brand,
                'slug' => Str::slug($brand),
                'logo' => null,
                'is_active' => true,
            ]);
        }

        Brand::factory()->count(5)->create([
            'shop_id' => $shop->id,
        ]);
    }
}