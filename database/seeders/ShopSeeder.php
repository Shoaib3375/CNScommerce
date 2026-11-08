<?php

namespace Database\Seeders;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShopSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $owner1 = User::where('email', 'owner1@example.com')->firstOrFail();
            $owner2 = User::where('email', 'owner2@example.com')->firstOrFail();

            Shop::create([
                'name'        => 'Shop Alpha',
                'slug'        => 'shop-alpha',
                'description' => 'The first demo shop owned by Owner One.',
                'status'      => true,
                'owner_id'    => $owner1->id,
            ]);

            Shop::create([
                'name'        => 'Shop Beta',
                'slug'        => 'shop-beta',
                'description' => 'The second demo shop owned by Owner Two.',
                'status'      => true,
                'owner_id'    => $owner2->id,
            ]);
        });
    }
}
