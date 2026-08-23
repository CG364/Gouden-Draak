<?php

use App\Models\DiningSession;
use App\Models\Discount;
use App\Models\Dish;
use App\Models\DishKind;
use App\Models\Order;
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

test('guests cannot access order management', function () {
    $this->get(route('admin.orders.index'))->assertRedirect('/admin/login');
});

test('a waiter cannot access order management', function () {
    $this->actingAs($this->waiter)
        ->get(route('admin.orders.index'))
        ->assertForbidden();
});

test('a cashier can view the order creation page with dishes to search', function () {
    $dish = Dish::factory()->create(['menu_number' => '12', 'price' => 5]);

    $this->actingAs($this->cashier)
        ->get(route('admin.orders.create'))
        ->assertSuccessful()
        ->assertSee($dish->name)
        ->assertSee('12');
});

test('the dish kind filter on the order creation page shows translated names, not raw json', function () {
    $dishKind = DishKind::factory()->create(['name' => ['nl' => 'Voorgerechten', 'en' => 'Starters']]);

    $response = $this->actingAs($this->cashier)
        ->withUnencryptedCookie('locale', 'nl')
        ->get(route('admin.orders.create'));

    $response->assertSuccessful();
    $response->assertSee('Voorgerechten');
    $response->assertDontSee('&quot;nl&quot;', escape: false);
});

test('a cashier can place an order with multiple products', function () {
    $dishOne = Dish::factory()->create(['price' => 5]);
    $dishTwo = Dish::factory()->create(['price' => 8]);

    $response = $this->actingAs($this->cashier)->post(route('admin.orders.store'), [
        'quantities' => [
            $dishOne->id => 2,
            $dishTwo->id => 1,
        ],
    ]);

    $response->assertRedirect(route('admin.orders.index'));

    $order = Order::query()->first();

    expect($order)->not->toBeNull();
    expect($order->placed_by)->toBe($this->cashier->id);

    $this->assertDatabaseHas('order_items', [
        'order_id' => $order->id,
        'dish_id' => $dishOne->id,
        'quantity' => 2,
        'unit_price' => 5,
    ]);
    $this->assertDatabaseHas('order_items', [
        'order_id' => $order->id,
        'dish_id' => $dishTwo->id,
        'quantity' => 1,
        'unit_price' => 8,
    ]);
});

test('a cashier can attach a note to an order item', function () {
    $dish = Dish::factory()->create(['price' => 5]);

    $response = $this->actingAs($this->cashier)->post(route('admin.orders.store'), [
        'quantities' => [$dish->id => 1],
        'notes' => [$dish->id => 'No peanuts, please'],
    ]);

    $response->assertRedirect(route('admin.orders.index'));

    $this->assertDatabaseHas('order_items', [
        'dish_id' => $dish->id,
        'notes' => 'No peanuts, please',
    ]);
});

test('an order uses the active discounted price for a dish instead of its regular price', function () {
    $dish = Dish::factory()->create(['price' => 10]);
    $discount = Discount::factory()->create([
        'starts_at' => now()->subHour(),
        'ends_at' => now()->addDay(),
    ]);
    $discount->dishes()->attach($dish->id, ['discounted_price' => 6.5]);

    $response = $this->actingAs($this->cashier)->post(route('admin.orders.store'), [
        'quantities' => [$dish->id => 3],
    ]);

    $response->assertRedirect(route('admin.orders.index'));

    $this->assertDatabaseHas('order_items', [
        'dish_id' => $dish->id,
        'quantity' => 3,
        'unit_price' => 6.5,
    ]);
});

test('placing an order requires at least one product', function () {
    $response = $this->actingAs($this->cashier)->post(route('admin.orders.store'), [
        'quantities' => [],
    ]);

    $response->assertInvalid(['quantities']);
});

test('an admin can view the order index and a single order', function () {
    $dish = Dish::factory()->create(['price' => 5]);
    $order = Order::factory()->create(['placed_by' => $this->cashier->id]);
    $order->items()->create([
        'dish_id' => $dish->id,
        'quantity' => 2,
        'unit_price' => 5,
    ]);

    $this->actingAs($this->admin)
        ->get(route('admin.orders.index'))
        ->assertSuccessful()
        ->assertSee($this->cashier->name);

    $this->actingAs($this->admin)
        ->get(route('admin.orders.show', $order))
        ->assertSuccessful()
        ->assertSee($dish->name)
        ->assertSee('10.00');
});

test('an order item note is visible on the order detail page', function () {
    $dish = Dish::factory()->create(['price' => 5]);
    $order = Order::factory()->create(['placed_by' => $this->cashier->id]);
    $order->items()->create([
        'dish_id' => $dish->id,
        'quantity' => 1,
        'unit_price' => 5,
        'notes' => 'Extra spicy',
    ]);

    $this->actingAs($this->admin)
        ->get(route('admin.orders.show', $order))
        ->assertSuccessful()
        ->assertSee('Extra spicy');
});

test('a takeout order shows the customer name instead of a staff member', function () {
    $dish = Dish::factory()->create(['price' => 5]);
    $order = Order::factory()->create(['placed_by' => null, 'customer_name' => 'Jane Doe', 'token' => 'test-token']);
    $order->items()->create([
        'dish_id' => $dish->id,
        'quantity' => 1,
        'unit_price' => 5,
    ]);

    $this->actingAs($this->admin)
        ->get(route('admin.orders.index'))
        ->assertSuccessful()
        ->assertSee('Jane Doe');

    $this->actingAs($this->admin)
        ->get(route('admin.orders.show', $order))
        ->assertSuccessful()
        ->assertSee('Jane Doe');
});

test('an order placed from the tablet shows the table number instead of a dash', function () {
    $dish = Dish::factory()->create(['price' => 5]);
    $table = Table::factory()->create(['nr' => 7]);
    $diningSession = DiningSession::factory()->for($table, 'table')->create();
    $order = Order::factory()->create([
        'placed_by' => null,
        'dining_session_id' => $diningSession->id,
    ]);
    $order->items()->create([
        'dish_id' => $dish->id,
        'quantity' => 1,
        'unit_price' => 5,
    ]);

    $this->actingAs($this->admin)
        ->get(route('admin.orders.index'))
        ->assertSuccessful()
        ->assertSee('Table 7');

    $this->actingAs($this->admin)
        ->get(route('admin.orders.show', $order))
        ->assertSuccessful()
        ->assertSee('Table 7');
});

test('a cashier can delete an order', function () {
    $order = Order::factory()->create(['placed_by' => $this->cashier->id]);

    $response = $this->actingAs($this->cashier)->delete(route('admin.orders.destroy', $order));

    $response->assertRedirect(route('admin.orders.index'));
    $this->assertDatabaseMissing('orders', ['id' => $order->id]);
});
