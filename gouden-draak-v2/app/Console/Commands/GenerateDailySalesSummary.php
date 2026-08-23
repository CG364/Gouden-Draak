<?php

namespace App\Console\Commands;

use App\Actions\Sales\BuildDailySalesReport;
use App\Exports\DailySalesExport;
use App\Models\DailySalesSummary;
use Carbon\CarbonImmutable;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

#[Signature('sales:generate-daily-summary {date? : The day to summarize (Y-m-d), defaults to yesterday}')]
#[Description('Generate an Excel sales summary for a single day and store it for admin download')]
class GenerateDailySalesSummary extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(BuildDailySalesReport $buildDailySalesReport): int
    {
        $date = $this->argument('date')
            ? CarbonImmutable::parse($this->argument('date'))->startOfDay()
            : CarbonImmutable::yesterday();

        $report = $buildDailySalesReport->handle($date);

        $filePath = 'sales-summaries/'.$date->toDateString().'.xlsx';

        Excel::store(
            new DailySalesExport($date, $report['rows'], $report['totalOrders'], $report['totalRevenue']),
            $filePath,
            'local',
        );

        // The `date` column is stored as a full datetime string by Eloquent's
        // `date` cast, so lookups must use `whereDate()` rather than an exact
        // string match against `toDateString()`.
        $summary = DailySalesSummary::query()->whereDate('date', $date->toDateString())->first()
            ?? new DailySalesSummary(['date' => $date->toDateString()]);

        $summary->fill([
            'total_orders' => $report['totalOrders'],
            'total_revenue' => $report['totalRevenue'],
            'file_path' => $filePath,
        ])->save();

        $this->info(sprintf(
            'Sales summary for %s generated: %d orders, €%s',
            $date->toDateString(),
            $report['totalOrders'],
            number_format($report['totalRevenue'], 2, ',', '.'),
        ));

        return self::SUCCESS;
    }
}
