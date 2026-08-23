<?php

use App\Models\DailySalesSummary;
use App\Models\Dish;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

uses(RefreshDatabase::class);

test('it generates and stores a daily sales summary for the given date', function () {
    Excel::fake();

    $dish = Dish::factory()->create();

    $order = Order::factory()->create(['created_at' => Carbon::parse('2026-08-20 12:00:00')]);
    OrderItem::factory()->for($order)->for($dish)->create(['quantity' => 2, 'unit_price' => 10]);

    $otherDayOrder = Order::factory()->create(['created_at' => Carbon::parse('2026-08-21 12:00:00')]);
    OrderItem::factory()->for($otherDayOrder)->for($dish)->create(['quantity' => 5, 'unit_price' => 10]);

    $this->artisan('sales:generate-daily-summary', ['date' => '2026-08-20'])->assertSuccessful();

    $summary = DailySalesSummary::query()->whereDate('date', '2026-08-20')->first();

    expect($summary)->not->toBeNull();
    expect($summary->total_orders)->toBe(1);
    expect((float) $summary->total_revenue)->toBe(20.0);
    expect($summary->file_path)->toBe('sales-summaries/2026-08-20.xlsx');

    Excel::assertStored('sales-summaries/2026-08-20.xlsx', 'local');
});

test('running the command again for the same date updates the existing summary', function () {
    Excel::fake();

    $dish = Dish::factory()->create();
    $order = Order::factory()->create(['created_at' => Carbon::parse('2026-08-20 09:00:00')]);
    OrderItem::factory()->for($order)->for($dish)->create(['quantity' => 1, 'unit_price' => 15]);

    $this->artisan('sales:generate-daily-summary', ['date' => '2026-08-20'])->assertSuccessful();

    $secondOrder = Order::factory()->create(['created_at' => Carbon::parse('2026-08-20 18:00:00')]);
    OrderItem::factory()->for($secondOrder)->for($dish)->create(['quantity' => 1, 'unit_price' => 15]);

    $this->artisan('sales:generate-daily-summary', ['date' => '2026-08-20'])->assertSuccessful();

    expect(DailySalesSummary::query()->whereDate('date', '2026-08-20')->count())->toBe(1);
    expect((float) DailySalesSummary::query()->whereDate('date', '2026-08-20')->first()->total_revenue)->toBe(30.0);
});

test('with no date argument it defaults to summarizing yesterday', function () {
    Excel::fake();
    Carbon::setTestNow('2026-08-23 10:00:00');

    $dish = Dish::factory()->create();
    $order = Order::factory()->create(['created_at' => Carbon::parse('2026-08-22 12:00:00')]);
    OrderItem::factory()->for($order)->for($dish)->create(['quantity' => 1, 'unit_price' => 5]);

    $this->artisan('sales:generate-daily-summary')->assertSuccessful();

    expect(DailySalesSummary::query()->whereDate('date', '2026-08-22')->exists())->toBeTrue();

    Carbon::setTestNow();
});
