<?php

namespace App\Actions\Discounts;

use App\Models\Discount;

class CreateDiscount
{
    /**
     * Create a discount and attach the selected dishes with their discounted prices.
     *
     * @param  array{starts_at: string, ends_at: string, dish_ids: array<int, int|string>, discounted_prices: array<int|string, int|string>}  $validated
     */
    public function handle(array $validated): Discount
    {
        $discount = Discount::query()->create([
            'starts_at' => $validated['starts_at'],
            'ends_at' => $validated['ends_at'],
        ]);

        $discount->dishes()->attach(
            collect($validated['dish_ids'])->mapWithKeys(fn ($dishId) => [
                (int) $dishId => ['discounted_price' => $validated['discounted_prices'][$dishId]],
            ])->all()
        );

        return $discount;
    }
}
