<?php

namespace Database\Factories;

use App\Models\SiteNavbarItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SiteNavbarItem>
 */
class SiteNavbarItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'header' => ['nl' => $this->faker->words(2, true), 'en' => $this->faker->words(2, true)],
            'page_id' => null,
            'foreign_url' => '/'.$this->faker->slug(),
            'order' => (SiteNavbarItem::query()->max('order') ?? -1) + 1,
        ];
    }
}
