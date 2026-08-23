<?php

namespace Database\Factories;

use App\Models\DiningSession;
use App\Models\Table;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<DiningSession>
 */
class DiningSessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $guestCount = fake()->numberBetween(1, DiningSession::MAX_GUESTS);

        return [
            'table_id' => Table::factory(),
            'opened_by' => User::factory(),
            'token' => Str::random(40),
            'started_at' => now(),
            'ended_at' => null,
            'guest_count' => $guestCount,
            'guest_ages' => fake()->randomElements(range(4, 70), $guestCount),
            'wants_extra_deluxe_menu' => fake()->boolean(),
        ];
    }

    /**
     * Indicate that the dining session has already been closed.
     */
    public function closed(): static
    {
        return $this->state(fn (array $attributes): array => [
            'ended_at' => now(),
        ]);
    }
}
