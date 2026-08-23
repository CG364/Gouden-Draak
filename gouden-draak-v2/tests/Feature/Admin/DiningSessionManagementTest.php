<?php

use App\Actions\DiningSessions\BuildSessionReceiptLines;
use App\Models\DiningSession;
use App\Models\Dish;
use App\Models\Table;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

test('guests cannot access dining session management', function () {
    $this->get(route('admin.dining-sessions.index'))->assertRedirect('/admin/login');
});

test('a cashier cannot access dining session management', function () {
    $this->actingAs($this->cashier)
        ->get(route('admin.dining-sessions.index'))
        ->assertForbidden();
});

test('a waiter can view the session creation page listing only free tables', function () {
    $freeTable = Table::factory()->create(['nr' => 1]);
    $occupiedTable = Table::factory()->create(['nr' => 2]);
    DiningSession::factory()->for($occupiedTable, 'table')->create();

    $response = $this->actingAs($this->waiter)->get(route('admin.dining-sessions.create'));

    $response->assertSuccessful();
    $response->assertSee('Table 1');
    $response->assertDontSee('Table 2');
});

test('a waiter can start a new dining session for a table', function () {
    $table = Table::factory()->create(['nr' => 7]);

    $response = $this->actingAs($this->waiter)->post(route('admin.dining-sessions.store'), [
        'table_id' => $table->id,
        'guest_count' => 3,
        'guest_ages' => '34, 36, 8',
        'wants_extra_deluxe_menu' => '1',
    ]);

    $diningSession = DiningSession::query()->first();

    $response->assertRedirect(route('admin.dining-sessions.show', $diningSession));
    expect($diningSession->table_id)->toBe($table->id);
    expect($diningSession->opened_by)->toBe($this->waiter->id);
    expect($diningSession->token)->not->toBeEmpty();
    expect($diningSession->ended_at)->toBeNull();
    expect($diningSession->guest_count)->toBe(3);
    expect($diningSession->guest_ages)->toBe([34, 36, 8]);
    expect($diningSession->wants_extra_deluxe_menu)->toBeTrue();
});

test('a new session cannot be started for a table that already has an active session', function () {
    $table = Table::factory()->create();
    DiningSession::factory()->for($table, 'table')->create();

    $response = $this->actingAs($this->waiter)->post(route('admin.dining-sessions.store'), [
        'table_id' => $table->id,
        'guest_count' => 2,
        'guest_ages' => '34, 36',
        'wants_extra_deluxe_menu' => '0',
    ]);

    $response->assertInvalid(['table_id']);
    expect(DiningSession::query()->count())->toBe(1);
});

test('the number of guest ages must match the guest count', function () {
    $table = Table::factory()->create();

    $response = $this->actingAs($this->waiter)->post(route('admin.dining-sessions.store'), [
        'table_id' => $table->id,
        'guest_count' => 3,
        'guest_ages' => '34, 36',
        'wants_extra_deluxe_menu' => '0',
    ]);

    $response->assertInvalid(['guest_ages']);
    expect(DiningSession::query()->count())->toBe(0);
});

test('the guest count cannot exceed the maximum', function () {
    $table = Table::factory()->create();

    $response = $this->actingAs($this->waiter)->post(route('admin.dining-sessions.store'), [
        'table_id' => $table->id,
        'guest_count' => DiningSession::MAX_GUESTS + 1,
        'guest_ages' => '1, 2, 3, 4, 5, 6, 7, 8, 9',
        'wants_extra_deluxe_menu' => '0',
    ]);

    $response->assertInvalid(['guest_count']);
    expect(DiningSession::query()->count())->toBe(0);
});

test('a waiter can view the tablet link for a session', function () {
    $table = Table::factory()->create(['nr' => 3]);
    $diningSession = DiningSession::factory()->for($table, 'table')->create();

    $response = $this->actingAs($this->waiter)->get(route('admin.dining-sessions.show', $diningSession));

    $response->assertSuccessful();
    $response->assertSee('Table 3');
    $response->assertSee(route('tablet.menu', $diningSession), escape: false);
});

test('a waiter can download a receipt pdf for a session', function () {
    $dish = Dish::factory()->create(['price' => 5]);
    $diningSession = DiningSession::factory()->create();
    $order = $diningSession->orders()->create();
    $order->items()->create([
        'dish_id' => $dish->id,
        'quantity' => 2,
        'unit_price' => 5,
    ]);

    $response = $this->actingAs($this->waiter)->get(route('admin.dining-sessions.receipt', $diningSession));

    $response->assertSuccessful();
    $response->assertHeader('content-type', 'application/pdf');
});

test('a cashier cannot download a session receipt', function () {
    $diningSession = DiningSession::factory()->create();

    $this->actingAs($this->cashier)
        ->get(route('admin.dining-sessions.receipt', $diningSession))
        ->assertForbidden();
});

test('quantities for the same dish across multiple rounds are merged on the receipt', function () {
    $dish = Dish::factory()->create(['price' => 5]);
    $diningSession = DiningSession::factory()->create();

    foreach ([2, 3] as $quantity) {
        $order = $diningSession->orders()->create();
        $order->items()->create([
            'dish_id' => $dish->id,
            'quantity' => $quantity,
            'unit_price' => 5,
        ]);
    }

    $diningSession->load('orders.items.dish', 'table');

    $lines = app(BuildSessionReceiptLines::class)->handle($diningSession);

    expect($lines)->toHaveCount(1);
    expect($lines->first()['quantity'])->toBe(5);
    expect($lines->first()['lineTotal'])->toBe(25.0);
});

test('items with different notes are kept as separate receipt lines', function () {
    $dish = Dish::factory()->create(['price' => 5]);
    $diningSession = DiningSession::factory()->create();

    $order = $diningSession->orders()->create();
    $order->items()->create(['dish_id' => $dish->id, 'quantity' => 1, 'unit_price' => 5, 'notes' => 'No onions']);
    $order->items()->create(['dish_id' => $dish->id, 'quantity' => 2, 'unit_price' => 5]);

    $diningSession->load('orders.items.dish', 'table');

    $lines = app(BuildSessionReceiptLines::class)->handle($diningSession);

    expect($lines)->toHaveCount(2);
    expect($lines->firstWhere('notes', 'No onions')['quantity'])->toBe(1);
    expect($lines->firstWhere('notes', null)['quantity'])->toBe(2);
});

test('a waiter can close a dining session', function () {
    $diningSession = DiningSession::factory()->create();

    $response = $this->actingAs($this->waiter)->patch(route('admin.dining-sessions.close', $diningSession));

    $response->assertRedirect(route('admin.dining-sessions.index'));
    expect($diningSession->fresh()->ended_at)->not->toBeNull();
});
