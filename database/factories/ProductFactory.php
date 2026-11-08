<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->words(3, true);

        return [
            'name'        => $name,
            'slug'        => Str::slug($name.'-'.uniqid()),
            'description' => fake()->paragraph(),
            'base_price'  => fake()->randomFloat(2, 10, 500),
            'thumbnail'   => 'https://picsum.photos/id/'.fake()->numberBetween(1, 500).'/600/600',
            'created_by'  => User::factory()->admin(),
        ];
    }
}
