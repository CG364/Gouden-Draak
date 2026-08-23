<?php

namespace App\Actions\Orders;

use App\Models\Order;

class BuildOrderQrText
{
    /**
     * Build the plain-text payload embedded in a takeout order's QR code, so
     * staff can read the order straight off a scan even if the confirmation
     * page or the admin system itself is unreachable.
     */
    public function handle(Order $order): string
    {
        $lines = [
            "De Gouden Draak - Order #{$order->id}",
            "Name: {$order->customer_name}",
        ];

        foreach ($order->items as $item) {
            $line = "{$item->quantity}x #{$item->dish->menu_number} {$item->dish->name}";

            if ($item->notes !== null) {
                $line .= " ({$item->notes})";
            }

            $lines[] = $line;
        }

        return implode("\n", $lines);
    }
}
