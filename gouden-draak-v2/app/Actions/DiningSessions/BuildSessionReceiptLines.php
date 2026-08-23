<?php

namespace App\Actions\DiningSessions;

use App\Models\DiningSession;
use App\Models\OrderItem;
use Illuminate\Support\Collection;

class BuildSessionReceiptLines
{
    /**
     * Build the itemized receipt lines for a dining session's bill, merging
     * quantities for the same dish at the same price and with the same note
     * across every round ordered during the session. Items with different
     * notes are kept as separate lines so a request like "no onions" isn't
     * silently merged away.
     *
     * Expects `orders.items.dish` to already be eager loaded on `$diningSession`.
     *
     * @return Collection<int, array{name: string, quantity: int, unitPrice: float, lineTotal: float, notes: ?string}>
     */
    public function handle(DiningSession $diningSession): Collection
    {
        return $diningSession->orders
            ->flatMap(fn ($order) => $order->items)
            ->groupBy(fn (OrderItem $item) => "{$item->dish_id}:{$item->unit_price}:{$item->notes}")
            ->map(function (Collection $items): array {
                /** @var OrderItem $first */
                $first = $items->first();

                return [
                    'name' => $first->dish->name,
                    'quantity' => $items->sum('quantity'),
                    'unitPrice' => (float) $first->unit_price,
                    'lineTotal' => $items->sum(fn (OrderItem $item): float => $item->quantity * (float) $item->unit_price),
                    'notes' => $first->notes,
                ];
            })
            ->values();
    }
}
