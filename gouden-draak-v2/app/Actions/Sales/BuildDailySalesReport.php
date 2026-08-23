<?php

namespace App\Actions\Sales;

use App\Models\Order;
use App\Models\OrderItem;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

class BuildDailySalesReport
{
    /**
     * Build a per-dish revenue breakdown plus order/revenue totals for the
     * given calendar day (using the order's placement time).
     *
     * @return array{date: CarbonInterface, rows: Collection<int, array{menu_number: string, name: string, quantity: int, revenue: float}>, totalOrders: int, totalRevenue: float}
     */
    public function handle(CarbonInterface $date): array
    {
        $orders = Order::query()
            ->whereBetween('created_at', [$date->copy()->startOfDay(), $date->copy()->endOfDay()])
            ->with('items.dish')
            ->get();

        $rows = $orders
            ->flatMap(fn (Order $order): Collection => $order->items)
            ->groupBy('dish_id')
            ->map(function (Collection $items): array {
                $dish = $items->first()->dish;

                return [
                    'menu_number' => $dish->menu_number,
                    'name' => $dish->name,
                    'quantity' => $items->sum('quantity'),
                    'revenue' => $items->sum(fn (OrderItem $item): float => $item->quantity * (float) $item->unit_price),
                ];
            })
            ->sortByDesc('revenue')
            ->values();

        return [
            'date' => $date,
            'rows' => $rows,
            'totalOrders' => $orders->count(),
            'totalRevenue' => (float) $rows->sum('revenue'),
        ];
    }
}
