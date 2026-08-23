<?php

namespace App\Actions\Discounts;

use App\Models\Dish;
use App\Models\DishKind;
use Illuminate\Support\Collection;

class ListDishKindsForDiscountSelection
{
    /**
     * List dish kinds with their dishes, grouped for the discount creation
     * page's dish picker.
     *
     * @return Collection<int, DishKind>
     */
    public function handle(): Collection
    {
        return DishKind::query()
            ->with('dishes')
            ->orderBy('id')
            ->get()
            ->filter(fn (DishKind $dishKind) => $dishKind->dishes->isNotEmpty())
            ->values()
            ->each(fn (DishKind $dishKind) => $dishKind->setRelation('dishes', Dish::sortByMenuNumber($dishKind->dishes)));
    }
}
