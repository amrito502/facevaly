<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            ['name' => 'Piece', 'short_name' => 'pc'],
            ['name' => 'Kilogram', 'short_name' => 'kg'],
            ['name' => 'Gram', 'short_name' => 'g'],
            ['name' => 'Liter', 'short_name' => 'L'],
            ['name' => 'Milliliter', 'short_name' => 'ml'],
            ['name' => 'Box', 'short_name' => 'box'],
            ['name' => 'Pack', 'short_name' => 'pack'],
        ];

        foreach ($units as $unit) {
            Unit::create([
                'name' => $unit['name'],
                'slug' => Str::slug($unit['name']),
                'short_name' => $unit['short_name'],
                'is_active' => true,
            ]);
        }
    }
}