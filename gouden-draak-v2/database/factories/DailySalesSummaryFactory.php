<?php

namespace Database\Factories;

use App\Models\DailySalesSummary;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DailySalesSummary>
 */
class DailySalesSummaryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = fake()->unique()->dateTimeBetween('-1 year', 'now')->format('Y-m-d');

        return [
            'date' => $date,
            'total_orders' => fake()->numberBetween(0, 50),
            'total_revenue' => fake()->randomFloat(2, 0, 2000),
            'file_path' => "sales-summaries/{$date}.xlsx",
        ];
    }
}
