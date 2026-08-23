<?php

namespace Database\Factories;

use App\Models\DishKind;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DishKind>
 */
class DishKindFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => [
                'nl' => fake()->words(2, true),
                'en' => fake()->words(2, true),
            ],
        ];
    }
}
