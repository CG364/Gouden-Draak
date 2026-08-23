<?php

use App\Models\DailySalesSummary;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::create(['name' => 'admin']);
    Role::create(['name' => 'cashier']);
    Role::create(['name' => 'waiter']);

    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin');

    $this->cashier = User::factory()->create();
    $this->cashier->assignRole('cashier');

    $this->waiter = User::factory()->create();
    $this->waiter->assignRole('waiter');
});

test('guests cannot access sales summaries', function () {
    $this->get(route('admin.sales-summaries.index'))->assertRedirect('/admin/login');
});

test('a cashier cannot access sales summaries', function () {
    $this->actingAs($this->cashier)
        ->get(route('admin.sales-summaries.index'))
        ->assertForbidden();
});

test('a waiter cannot access sales summaries', function () {
    $this->actingAs($this->waiter)
        ->get(route('admin.sales-summaries.index'))
        ->assertForbidden();
});

test('an admin can view the list of generated sales summaries', function () {
    DailySalesSummary::factory()->create(['date' => '2026-08-20', 'total_orders' => 12, 'total_revenue' => 345.67]);

    $response = $this->actingAs($this->admin)->get(route('admin.sales-summaries.index'));

    $response->assertSuccessful();
    $response->assertSee('20-08-2026');
    $response->assertSee('345,67');
});

test('an admin can download the excel file for a sales summary', function () {
    Storage::fake('local');
    Storage::disk('local')->put('sales-summaries/2026-08-20.xlsx', 'fake-excel-contents');

    $salesSummary = DailySalesSummary::factory()->create([
        'date' => '2026-08-20',
        'file_path' => 'sales-summaries/2026-08-20.xlsx',
    ]);

    $response = $this->actingAs($this->admin)->get(route('admin.sales-summaries.download', $salesSummary));

    $response->assertDownload('omzet-2026-08-20.xlsx');
});

test('a cashier cannot download a sales summary', function () {
    Storage::fake('local');
    Storage::disk('local')->put('sales-summaries/2026-08-20.xlsx', 'fake-excel-contents');

    $salesSummary = DailySalesSummary::factory()->create([
        'date' => '2026-08-20',
        'file_path' => 'sales-summaries/2026-08-20.xlsx',
    ]);

    $this->actingAs($this->cashier)
        ->get(route('admin.sales-summaries.download', $salesSummary))
        ->assertForbidden();
});
