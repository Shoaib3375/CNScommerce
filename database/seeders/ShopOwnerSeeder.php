<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ShopOwnerSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            User::create([
                'name'     => 'Owner One',
                'email'    => 'owner1@example.com',
                'password' => Hash::make('password'),
                'role'     => User::ROLE_SHOP_OWNER,
            ]);

            User::create([
                'name'     => 'Owner Two',
                'email'    => 'owner2@example.com',
                'password' => Hash::make('password'),
                'role'     => User::ROLE_SHOP_OWNER,
            ]);
        });
    }
}
