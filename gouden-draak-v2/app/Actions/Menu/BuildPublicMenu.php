<?php

namespace App\Actions\Menu;

use App\Models\Dish;
use App\Models\DishKind;
use Illuminate\Support\Collection;

class BuildPublicMenu
{
    /**
     * Build the dish kinds and dishes shown on the public menu page, including
     * each dish's currently active discounted price, if any.
     *
     * @return Collection<int, array{id: int, name: string, dishes: Collection<int, array{id: int, menuNumber: string, name: string, description: string, price: float, discountedPrice: ?float}>}>
     */
    public function handle(): Collection
    {
        return DishKind::query()
            ->with(['dishes' => fn ($query) => $query->with(['discounts' => fn ($discountQuery) => $discountQuery->active()])])
            ->orderBy('id')
            ->get()
            ->filter(fn (DishKind $dishKind) => $dishKind->dishes->isNotEmpty())
            ->values()
            ->map(fn (DishKind $dishKind) => [
                'id' => $dishKind->id,
                'name' => $dishKind->name,
                'dishes' => Dish::sortByMenuNumber($dishKind->dishes)->map(fn (Dish $dish) => [
                    'id' => $dish->id,
                    'menuNumber' => $dish->menu_number,
                    'name' => $dish->name,
                    'description' => $dish->description,
                    'price' => (float) $dish->price,
                    'discountedPrice' => $dish->discounts->isNotEmpty()
                        ? (float) $dish->discounts->first()->pivot->discounted_price
                        : null,
                ])->values(),
            ]);
    }
}
