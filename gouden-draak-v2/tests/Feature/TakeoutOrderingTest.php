<?php

use App\Models\Dish;
use App\Models\DishKind;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('the takeout order page shows orderable dishes', function () {
    $dishKind = DishKind::factory()->create(['name' => ['nl' => 'Soep', 'en' => 'Soup']]);
    $dish = Dish::factory()->create([
        'dish_kind' => $dishKind->id,
        'menu_number' => '12',
        'name' => ['nl' => 'Kippensoep', 'en' => 'Chicken soup'],
    ]);

    $response = $this->withUnencryptedCookie('locale', 'nl')->get(route('order.create'));

    $response->assertSuccessful();
    $response->assertSee('Kippensoep');
    $response->assertSee($dish->menu_number);
});

test('a customer can place a takeout order and is redirected to a confirmation page', function () {
    $dish = Dish::factory()->create(['price' => 5]);

    $response = $this->post(route('order.store'), [
        'customer_name' => 'Jane Doe',
        'quantities' => [$dish->id => 2],
    ]);

    $order = Order::query()->first();

    expect($order)->not->toBeNull();
    expect($order->customer_name)->toBe('Jane Doe');
    expect($order->token)->not->toBeEmpty();
    expect($order->placed_by)->toBeNull();
    expect($order->dining_session_id)->toBeNull();

    $response->assertRedirect(route('order.show', ['order' => $order->token]));

    $this->assertDatabaseHas('order_items', [
        'order_id' => $order->id,
        'dish_id' => $dish->id,
        'quantity' => 2,
        'unit_price' => 5,
    ]);
});

test('a customer can attach a note to a takeout order item', function () {
    $dish = Dish::factory()->create(['price' => 5]);

    $this->post(route('order.store'), [
        'customer_name' => 'Jane Doe',
        'quantities' => [$dish->id => 1],
        'notes' => [$dish->id => 'Allergic to peanuts'],
    ]);

    $this->assertDatabaseHas('order_items', [
        'dish_id' => $dish->id,
        'notes' => 'Allergic to peanuts',
    ]);
});

test('an order item note appears on the confirmation page and in the qr text', function () {
    $dish = Dish::factory()->create(['price' => 5]);

    $this->post(route('order.store'), [
        'customer_name' => 'Jane Doe',
        'quantities' => [$dish->id => 1],
        'notes' => [$dish->id => 'No onions'],
    ]);

    $order = Order::query()->first();

    $response = $this->get(route('order.show', ['order' => $order->token]));

    $response->assertSuccessful();
    $response->assertSee('No onions');
});

test('placing a takeout order requires a customer name', function () {
    $dish = Dish::factory()->create();

    $response = $this->post(route('order.store'), [
        'customer_name' => '',
        'quantities' => [$dish->id => 1],
    ]);

    $response->assertInvalid(['customer_name']);
    expect(Order::query()->count())->toBe(0);
});

test('placing a takeout order requires at least one product', function () {
    $response = $this->post(route('order.store'), [
        'customer_name' => 'Jane Doe',
        'quantities' => [],
    ]);

    $response->assertInvalid(['quantities']);
    expect(Order::query()->count())->toBe(0);
});

test('the confirmation page shows the order number, customer name and a qr code', function () {
    $dish = Dish::factory()->create(['menu_number' => '7', 'price' => 5]);

    $this->post(route('order.store'), [
        'customer_name' => 'Jane Doe',
        'quantities' => [$dish->id => 3],
    ]);

    $order = Order::query()->first();

    $response = $this->get(route('order.show', ['order' => $order->token]));

    $response->assertSuccessful();
    $response->assertSee('Jane Doe');
    $response->assertSee('#'.$order->id, escape: false);
    $response->assertSee($dish->menu_number);
    $response->assertSee('id="order-confirmation-qr"', escape: false);
});

test('a takeout order cannot be looked up by its numeric id', function () {
    $dish = Dish::factory()->create();

    $this->post(route('order.store'), [
        'customer_name' => 'Jane Doe',
        'quantities' => [$dish->id => 1],
    ]);

    $order = Order::query()->first();

    $this->get('/order/'.$order->id)->assertNotFound();
});
