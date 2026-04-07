<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Address;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            Address::factory()->count(2)->create([
                'user_id' => $user->id,
            ]);

            $firstAddress = $user->addresses()->first();

            if ($firstAddress) {
                $firstAddress->update([
                    'is_default' => true,
                    'type' => 'shipping',
                ]);
            }
        }
    }
}