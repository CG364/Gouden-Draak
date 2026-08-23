<?php

namespace Database\Factories;

use App\Models\ServiceRequest;
use App\Models\Table;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ServiceRequest>
 */
class ServiceRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'table_id' => Table::factory(),
            'handled' => false,
        ];
    }

    /**
     * Indicate that the service request has already been handled.
     */
    public function handled(): static
    {
        return $this->state(fn (array $attributes): array => [
            'handled' => true,
        ]);
    }
}
