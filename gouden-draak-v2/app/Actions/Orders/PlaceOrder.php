<?php

namespace App\Actions\Orders;

use App\Models\DiningSession;
use App\Models\Dish;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Support\Str;

class PlaceOrder
{
    /**
     * Create an order with a line item per dish, using each dish's currently
     * active discounted price instead of its regular price where applicable.
     *
     * Pass a `$placedBy` staff user for orders placed on a customer's behalf
     * (e.g. by a cashier), a `$diningSession` for orders a customer places
     * themselves from a table's tablet, or a `$customerName` for a takeout
     * order placed on the public website (which also gets a private access
     * token, so the customer's confirmation page can't be guessed by ID).
     *
     * @param  array<int|string, int>  $quantities  dish ID => quantity
     * @param  array<int|string, ?string>  $notes  dish ID => customer note (e.g. "no onions")
     */
    public function handle(array $quantities, ?User $placedBy = null, ?DiningSession $diningSession = null, ?string $customerName = null, array $notes = []): Order
    {
        $dishes = Dish::query()
            ->whereIn('id', array_keys($quantities))
            ->with(['discounts' => fn ($query) => $query->active()])
            ->get()
            ->keyBy('id');

        $order = Order::query()->create([
            'placed_by' => $placedBy?->id,
            'dining_session_id' => $diningSession?->id,
            'customer_name' => $customerName,
            'token' => $customerName !== null ? Str::random(40) : null,
        ]);

        foreach ($quantities as $dishId => $quantity) {
            $dish = $dishes[$dishId];
            $note = trim((string) ($notes[$dishId] ?? ''));

            OrderItem::query()->create([
                'order_id' => $order->id,
                'dish_id' => $dish->id,
                'quantity' => $quantity,
                'unit_price' => $dish->discounts->isNotEmpty()
                    ? $dish->discounts->first()->pivot->discounted_price
                    : $dish->price,
                'notes' => $note !== '' ? $note : null,
            ]);
        }

        return $order;
    }
}
