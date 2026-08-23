<?php

use App\Models\DiningSession;
use App\Models\Dish;
use App\Models\DishKind;
use App\Models\Table;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('the tablet menu page shows the table number and orderable dishes', function () {
    $table = Table::factory()->create(['nr' => 4]);
    $diningSession = DiningSession::factory()->for($table, 'table')->create();
    $dishKind = DishKind::factory()->create(['name' => ['nl' => 'Soep', 'en' => 'Soup']]);
    $dish = Dish::factory()->create([
        'dish_kind' => $dishKind->id,
        'menu_number' => '12',
        'name' => ['nl' => 'Kippensoep', 'en' => 'Chicken soup'],
    ]);

    $response = $this->withUnencryptedCookie('locale', 'nl')->get(route('tablet.menu', $diningSession));

    $response->assertSuccessful();
    $response->assertSee('data-table-nr="4"', escape: false);
    $response->assertSee('Kippensoep');
    $response->assertSee($dish->menu_number);
});

test('a nonexistent tablet token results in a 404', function () {
    $this->get('/tablet/does-not-exist')->assertNotFound();
});

test('a closed dining session cannot be viewed or ordered from', function () {
    $diningSession = DiningSession::factory()->closed()->create();

    $this->get(route('tablet.menu', $diningSession))->assertStatus(410);

    $this->post(route('tablet.orders.store', $diningSession), [
        'quantities' => [],
    ])->assertStatus(410);
});

test('a closed dining session shows a friendly ended message instead of a raw error', function () {
    $diningSession = DiningSession::factory()->closed()->create();

    $this->get(route('tablet.menu', $diningSession))
        ->assertStatus(410)
        ->assertSee('This table session has ended');
});

test('the tablet status endpoint reports the session is active until it is closed', function () {
    $diningSession = DiningSession::factory()->create();

    $this->getJson(route('tablet.status', $diningSession))
        ->assertSuccessful()
        ->assertJson(['active' => true]);

    $diningSession->update(['ended_at' => now()]);

    $this->getJson(route('tablet.status', $diningSession))->assertStatus(410);
});

test('a dining session can place an order', function () {
    $dish = Dish::factory()->create(['price' => 5]);
    $diningSession = DiningSession::factory()->create();

    $response = $this->post(route('tablet.orders.store', $diningSession), [
        'quantities' => [$dish->id => 2],
    ]);

    $response->assertRedirect(route('tablet.menu', $diningSession));

    $order = $diningSession->orders()->first();
    expect($order)->not->toBeNull();
    expect($order->placed_by)->toBeNull();

    $this->assertDatabaseHas('order_items', [
        'order_id' => $order->id,
        'dish_id' => $dish->id,
        'quantity' => 2,
        'unit_price' => 5,
    ]);
});

test('a customer can attach a note to a tablet order item', function () {
    $dish = Dish::factory()->create(['price' => 5]);
    $diningSession = DiningSession::factory()->create();

    $this->post(route('tablet.orders.store', $diningSession), [
        'quantities' => [$dish->id => 1],
        'notes' => [$dish->id => 'No onions'],
    ]);

    $this->assertDatabaseHas('order_items', [
        'dish_id' => $dish->id,
        'notes' => 'No onions',
    ]);
});

test('placing a tablet order requires at least one product', function () {
    $diningSession = DiningSession::factory()->create();

    $response = $this->post(route('tablet.orders.store', $diningSession), [
        'quantities' => [],
    ]);

    $response->assertInvalid(['quantities']);
});

test('a dining session cannot place a second order before the cooldown has passed', function () {
    $dish = Dish::factory()->create(['price' => 5]);
    $diningSession = DiningSession::factory()->create();

    $this->post(route('tablet.orders.store', $diningSession), ['quantities' => [$dish->id => 1]])
        ->assertRedirect(route('tablet.menu', $diningSession));

    $response = $this->post(route('tablet.orders.store', $diningSession), ['quantities' => [$dish->id => 1]]);

    $response->assertInvalid(['quantities']);
    expect($diningSession->orders()->count())->toBe(1);
});

test('a dining session can place a second order once the cooldown has passed', function () {
    $dish = Dish::factory()->create(['price' => 5]);
    $diningSession = DiningSession::factory()->create();

    $this->post(route('tablet.orders.store', $diningSession), ['quantities' => [$dish->id => 1]])
        ->assertRedirect(route('tablet.menu', $diningSession));

    $this->travel(DiningSession::ORDER_COOLDOWN_MINUTES + 1)->minutes();

    $response = $this->post(route('tablet.orders.store', $diningSession), ['quantities' => [$dish->id => 1]]);

    $response->assertRedirect(route('tablet.menu', $diningSession));
    expect($diningSession->orders()->count())->toBe(2);
});

test('the tablet menu page includes each past order line\'s dish id for reordering', function () {
    $dish = Dish::factory()->create(['price' => 5]);
    $diningSession = DiningSession::factory()->create();

    $this->post(route('tablet.orders.store', $diningSession), ['quantities' => [$dish->id => 2]]);

    $response = $this->get(route('tablet.menu', $diningSession));

    $response->assertSuccessful();
    $response->assertSee('&quot;dishId&quot;:'.$dish->id, escape: false);
});

test('the tablet menu page includes each past order line\'s note for reordering', function () {
    $dish = Dish::factory()->create(['price' => 5]);
    $diningSession = DiningSession::factory()->create();

    $this->post(route('tablet.orders.store', $diningSession), [
        'quantities' => [$dish->id => 2],
        'notes' => [$dish->id => 'No onions'],
    ]);

    $response = $this->get(route('tablet.menu', $diningSession));

    $response->assertSuccessful();
    $response->assertSee('&quot;notes&quot;:&quot;No onions&quot;', escape: false);
});

test('a dining session cannot place more than the maximum number of orders', function () {
    $dish = Dish::factory()->create(['price' => 5]);
    $diningSession = DiningSession::factory()->create();

    foreach (range(1, DiningSession::MAX_ORDERS) as $round) {
        $this->post(route('tablet.orders.store', $diningSession), ['quantities' => [$dish->id => 1]])
            ->assertRedirect(route('tablet.menu', $diningSession));

        $this->travel(DiningSession::ORDER_COOLDOWN_MINUTES + 1)->minutes();
    }

    expect($diningSession->orders()->count())->toBe(DiningSession::MAX_ORDERS);

    $response = $this->post(route('tablet.orders.store', $diningSession), ['quantities' => [$dish->id => 1]]);

    $response->assertInvalid(['quantities']);
    expect($diningSession->orders()->count())->toBe(DiningSession::MAX_ORDERS);
});
