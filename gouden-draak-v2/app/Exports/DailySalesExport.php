<?php

namespace App\Exports;

use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class DailySalesExport implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    /**
     * @param  Collection<int, array{menu_number: string, name: string, quantity: int, revenue: float}>  $rows
     */
    public function __construct(
        private readonly CarbonInterface $date,
        private readonly Collection $rows,
        private readonly int $totalOrders,
        private readonly float $totalRevenue,
    ) {}

    /**
     * Get the rows to export, with a totals row appended.
     *
     * @return Collection<int, array{menu_number: string, name: string, quantity: int, revenue: float}>
     */
    public function collection(): Collection
    {
        return $this->rows->concat([[
            'menu_number' => '',
            'name' => __('sales.total'),
            'quantity' => $this->rows->sum('quantity'),
            'revenue' => $this->totalRevenue,
        ]]);
    }

    /**
     * @return array<int, string>
     */
    public function headings(): array
    {
        return [
            __('sales.menu_number'),
            __('sales.dish'),
            __('sales.quantity_sold'),
            __('sales.revenue'),
        ];
    }

    /**
     * @param  array{menu_number: string, name: string, quantity: int, revenue: float}  $row
     * @return array<int, string|int>
     */
    public function map(mixed $row): array
    {
        return [
            $row['menu_number'],
            $row['name'],
            $row['quantity'],
            number_format($row['revenue'], 2, ',', '.'),
        ];
    }

    public function title(): string
    {
        return sprintf('%s (%d %s)', $this->date->format('d-m-Y'), $this->totalOrders, __('sales.orders'));
    }
}
