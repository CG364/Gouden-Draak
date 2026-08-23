<?php

use App\Models\Dish;
use App\Models\DishKind;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::create(['name' => 'admin']);

    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin');
    $this->dishKind = DishKind::factory()->create();
});

test('guests cannot access dish management', function () {
    $this->get(route('admin.dishes.index'))->assertRedirect('/admin/login');
});

test('an admin can view the dishes index', function () {
    $dish = Dish::factory()->create(['dish_kind' => $this->dishKind->id]);

    $this->actingAs($this->admin)
        ->get(route('admin.dishes.index'))
        ->assertSuccessful()
        ->assertSee($dish->menu_number);
});

test('an admin can create a dish', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.dishes.store'), [
        'menu_number' => '42',
        'dish_kind' => $this->dishKind->id,
        'price' => '12.50',
        'name' => ['nl' => 'Loempia', 'en' => 'Spring roll'],
        'description' => ['nl' => 'Lekker', 'en' => 'Tasty'],
    ]);

    $response->assertRedirect(route('admin.dishes.index'));

    $dish = Dish::query()->where('menu_number', '42')->firstOrFail();
    expect($dish->getTranslation('name', 'en', false))->toBe('Spring roll');
    expect((float) $dish->price)->toBe(12.5);
    expect($dish->dish_kind)->toBe($this->dishKind->id);
});

test('creating a dish requires an existing dish kind', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.dishes.store'), [
        'menu_number' => '42',
        'dish_kind' => 999,
        'price' => '12.50',
        'name' => ['nl' => 'Loempia', 'en' => 'Spring roll'],
        'description' => ['nl' => 'Lekker', 'en' => 'Tasty'],
    ]);

    $response->assertInvalid(['dish_kind']);
});

test('an admin can update a dish', function () {
    $dish = Dish::factory()->create(['dish_kind' => $this->dishKind->id]);
    $otherKind = DishKind::factory()->create();

    $response = $this->actingAs($this->admin)->put(route('admin.dishes.update', $dish), [
        'menu_number' => $dish->menu_number,
        'dish_kind' => $otherKind->id,
        'price' => '9.99',
        'name' => ['nl' => 'Bijgewerkt', 'en' => 'Updated'],
        'description' => ['nl' => 'Bijgewerkt', 'en' => 'Updated'],
    ]);

    $response->assertRedirect(route('admin.dishes.index'));

    $dish->refresh();
    expect($dish->dish_kind)->toBe($otherKind->id);
    expect((float) $dish->price)->toBe(9.99);
});

test('an admin can create a dish with an alphanumeric menu number', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.dishes.store'), [
        'menu_number' => '58A',
        'dish_kind' => $this->dishKind->id,
        'price' => '12.50',
        'name' => ['nl' => 'Loempia', 'en' => 'Spring roll'],
        'description' => ['nl' => 'Lekker', 'en' => 'Tasty'],
    ]);

    $response->assertRedirect(route('admin.dishes.index'));

    $dish = Dish::query()->where('menu_number', '58A')->firstOrFail();
    expect($dish->menu_number)->toBe('58A');
});

test('the dishes index sorts menu numbers naturally, not lexicographically', function () {
    Dish::factory()->create(['dish_kind' => $this->dishKind->id, 'menu_number' => '10']);
    Dish::factory()->create(['dish_kind' => $this->dishKind->id, 'menu_number' => '2']);
    Dish::factory()->create(['dish_kind' => $this->dishKind->id, 'menu_number' => 'M1']);
    Dish::factory()->create(['dish_kind' => $this->dishKind->id, 'menu_number' => '58A']);

    $response = $this->actingAs($this->admin)->get(route('admin.dishes.index'));

    $response->assertSuccessful();

    $body = $response->getContent();
    $positions = collect(['2', '10', '58A', 'M1'])->map(fn (string $menuNumber) => strpos($body, ">{$menuNumber}<"));

    expect($positions->values()->all())->toBe($positions->sort()->values()->all());
});

test('an admin can delete a dish', function () {
    $dish = Dish::factory()->create(['dish_kind' => $this->dishKind->id]);

    $response = $this->actingAs($this->admin)->delete(route('admin.dishes.destroy', $dish));

    $response->assertRedirect(route('admin.dishes.index'));
    $this->assertDatabaseMissing('dishes', ['id' => $dish->id]);
});
