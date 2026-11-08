<?php

namespace Database\Factories;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Shop>
 */
class ShopFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->company();

        return [
            'name'        => $name,
            'slug'        => Str::slug($name.'-'.fake()->numerify('###')),
            'description' => fake()->paragraph(),
            'status'      => true,
            'owner_id'    => User::factory()->shopOwner(),
        ];
    }
}
