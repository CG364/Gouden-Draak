<?php

use App\Models\Discount;
use App\Models\Dish;
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

test('guests cannot access discount management', function () {
    $this->get(route('admin.discounts.index'))->assertRedirect('/admin/login');
});

test('a waiter cannot access discount management', function () {
    $this->actingAs($this->waiter)
        ->get(route('admin.discounts.index'))
        ->assertForbidden();
});

test('a cashier can view the discount index', function () {
    $dish = Dish::factory()->create(['price' => 10]);
    $discount = Discount::factory()->create();
    $discount->dishes()->attach($dish->id, ['discounted_price' => 7.5]);

    $this->actingAs($this->cashier)
        ->get(route('admin.discounts.index'))
        ->assertSuccessful()
        ->assertSee($dish->name);
});

test('an admin can view the discount index', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.discounts.index'))
        ->assertSuccessful();
});

test('a cashier can create a discount on multiple products', function () {
    $dishOne = Dish::factory()->create(['price' => 10]);
    $dishTwo = Dish::factory()->create(['price' => 20]);

    $response = $this->actingAs($this->cashier)->post(route('admin.discounts.store'), [
        'starts_at' => '2026-08-20T10:00',
        'ends_at' => '2026-08-21T10:00',
        'dish_ids' => [$dishOne->id, $dishTwo->id],
        'discounted_prices' => [
            $dishOne->id => '7.50',
            $dishTwo->id => '15.00',
        ],
    ]);

    $response->assertRedirect(route('admin.discounts.index'));

    $discount = Discount::query()->first();

    $this->assertDatabaseHas('discount_dish', [
        'discount_id' => $discount->id,
        'dish_id' => $dishOne->id,
        'discounted_price' => 7.5,
    ]);
    $this->assertDatabaseHas('discount_dish', [
        'discount_id' => $discount->id,
        'dish_id' => $dishTwo->id,
        'discounted_price' => 15,
    ]);
});

test('a discount cannot span more than one week', function () {
    $dish = Dish::factory()->create(['price' => 10]);

    $response = $this->actingAs($this->cashier)->post(route('admin.discounts.store'), [
        'starts_at' => '2026-08-20T10:00',
        'ends_at' => '2026-08-29T10:00',
        'dish_ids' => [$dish->id],
        'discounted_prices' => [$dish->id => '7.50'],
    ]);

    $response->assertInvalid(['ends_at']);
});

test('creating a discount requires at least one product', function () {
    $response = $this->actingAs($this->cashier)->post(route('admin.discounts.store'), [
        'starts_at' => '2026-08-20T10:00',
        'ends_at' => '2026-08-21T10:00',
        'dish_ids' => [],
        'discounted_prices' => [],
    ]);

    $response->assertInvalid(['dish_ids']);
});

test('creating a discount requires a discounted price for each selected product', function () {
    $dish = Dish::factory()->create(['price' => 10]);

    $response = $this->actingAs($this->cashier)->post(route('admin.discounts.store'), [
        'starts_at' => '2026-08-20T10:00',
        'ends_at' => '2026-08-21T10:00',
        'dish_ids' => [$dish->id],
        'discounted_prices' => [],
    ]);

    $response->assertInvalid(['dish_ids']);
});

test('a cashier can delete a discount', function () {
    $dish = Dish::factory()->create(['price' => 10]);
    $discount = Discount::factory()->create();
    $discount->dishes()->attach($dish->id, ['discounted_price' => 7.5]);

    $response = $this->actingAs($this->cashier)->delete(route('admin.discounts.destroy', $discount));

    $response->assertRedirect(route('admin.discounts.index'));
    $this->assertDatabaseMissing('discounts', ['id' => $discount->id]);
    $this->assertDatabaseMissing('discount_dish', ['discount_id' => $discount->id]);
});
