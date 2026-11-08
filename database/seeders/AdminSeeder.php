<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            User::create([
                'name'     => 'Admin',
                'email'    => 'admin@example.com',
                'password' => Hash::make('password'),
                'role'     => User::ROLE_ADMIN,
            ]);
        });
    }
}
