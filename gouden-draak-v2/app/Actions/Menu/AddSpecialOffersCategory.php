<?php

namespace App\Actions\Menu;

use Illuminate\Support\Collection;

class AddSpecialOffersCategory
{
    /**
     * Prepend a synthetic "Special Offers" category listing every currently
     * discounted dish, in addition to each dish still appearing under its
     * normal category, so deals are easy to find without hiding where a
     * dish normally lives.
     *
     * @param  Collection<int, array{id: int, name: string, dishes: Collection<int, array<string, mixed>>}>  $dishKinds
     * @return Collection<int, array{id: int|string, name: string, dishes: Collection<int, array<string, mixed>>}>
     */
    public function handle(Collection $dishKinds): Collection
    {
        $discountedDishes = $dishKinds
            ->flatMap(fn (array $dishKind): Collection => $dishKind['dishes'])
            ->filter(fn (array $dish): bool => $dish['discountedPrice'] !== null)
            ->values();

        if ($discountedDishes->isEmpty()) {
            return $dishKinds;
        }

        return $dishKinds->prepend([
            'id' => 'special-offers',
            'name' => __('menu.special_offers_category'),
            'dishes' => $discountedDishes,
        ]);
    }
}
