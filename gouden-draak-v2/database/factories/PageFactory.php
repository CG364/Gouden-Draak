<?php

namespace Database\Factories;

use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Page>
 */
class PageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'slug' => fake()->unique()->slug(),
            'title' => [
                'nl' => fake()->sentence(3),
                'en' => fake()->sentence(3),
            ],
            'content' => [
                'nl' => '<p>'.fake()->paragraph().'</p>',
                'en' => '<p>'.fake()->paragraph().'</p>',
            ],
        ];
    }
}
