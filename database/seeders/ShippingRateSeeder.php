<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShippingRateSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('shipping_rates')->insert([
            [
                'location' => 'Inside Dhaka',
                'shipping_cost' => 60.00,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'location' => 'Outside Dhaka',
                'shipping_cost' => 120.00,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'location' => 'Remote Area',
                'shipping_cost' => 180.00,
                'status' => 'inactive',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}