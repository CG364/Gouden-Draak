<?php

namespace App\Actions\Orders;

use App\Models\Dish;
use Illuminate\Support\Collection;

class ListOrderableDishes
{
    /**
     * List dishes for the order creation page's search and filter list,
     * including each dish's currently active discounted price, if any.
     *
     * @return Collection<int, array{id: int, menuNumber: string, name: string, price: float, discountedPrice: ?float, dishKindId: int}>
     */
    public function handle(): Collection
    {
        $dishes = Dish::query()
            ->with(['discounts' => fn ($query) => $query->active()])
            ->get();

        return Dish::sortByMenuNumber($dishes)->map(fn (Dish $dish) => [
            'id' => $dish->id,
            'menuNumber' => $dish->menu_number,
            'name' => $dish->name,
            'price' => (float) $dish->price,
            'discountedPrice' => $dish->discounts->isNotEmpty()
                ? (float) $dish->discounts->first()->pivot->discounted_price
                : null,
            'dishKindId' => $dish->dish_kind,
        ])->values();
    }
}
