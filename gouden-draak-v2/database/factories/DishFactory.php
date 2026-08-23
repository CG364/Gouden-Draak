<?php

namespace Database\Factories;

use App\Models\Dish;
use App\Models\DishKind;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Dish>
 */
class DishFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'menu_number' => (string) fake()->unique()->numberBetween(1, 200),
            'name' => [
                'nl' => fake()->words(3, true),
                'en' => fake()->words(3, true),
            ],
            'description' => [
                'nl' => fake()->sentence(),
                'en' => fake()->sentence(),
            ],
            'dish_kind' => DishKind::factory(),
            'price' => fake()->randomFloat(2, 5, 40),
        ];
    }
}
