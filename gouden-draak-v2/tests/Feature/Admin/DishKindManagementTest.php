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
});

test('guests cannot access dish kind management', function () {
    $this->get(route('admin.dish-kinds.index'))->assertRedirect('/admin/login');
});

test('an admin can view the dish kinds index', function () {
    $dishKind = DishKind::factory()->create();

    $this->actingAs($this->admin)
        ->get(route('admin.dish-kinds.index'))
        ->assertSuccessful()
        ->assertSee($dishKind->name);
});

test('an admin can create a dish kind', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.dish-kinds.store'), [
        'name' => ['nl' => 'Soep', 'en' => 'Soup'],
    ]);

    $response->assertRedirect(route('admin.dish-kinds.index'));

    $dishKind = DishKind::query()->firstOrFail();
    expect($dishKind->getTranslation('name', 'en', false))->toBe('Soup');
});

test('creating a dish kind requires a translation for every locale', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.dish-kinds.store'), [
        'name' => ['nl' => 'Soep'],
    ]);

    $response->assertInvalid(['name.en']);
});

test('an admin can update a dish kind', function () {
    $dishKind = DishKind::factory()->create();

    $response = $this->actingAs($this->admin)->put(route('admin.dish-kinds.update', $dishKind), [
        'name' => ['nl' => 'Bijgewerkt', 'en' => 'Updated'],
    ]);

    $response->assertRedirect(route('admin.dish-kinds.index'));
    expect($dishKind->fresh()->getTranslation('name', 'en', false))->toBe('Updated');
});

test('an admin can delete a dish kind that has no dishes', function () {
    $dishKind = DishKind::factory()->create();

    $response = $this->actingAs($this->admin)->delete(route('admin.dish-kinds.destroy', $dishKind));

    $response->assertRedirect(route('admin.dish-kinds.index'));
    $this->assertDatabaseMissing('dish_kinds', ['id' => $dishKind->id]);
});

test('an admin cannot delete a dish kind that still has dishes', function () {
    $dishKind = DishKind::factory()->create();
    Dish::factory()->create(['dish_kind' => $dishKind->id]);

    $response = $this->actingAs($this->admin)->delete(route('admin.dish-kinds.destroy', $dishKind));

    $response->assertRedirect(route('admin.dish-kinds.index'));
    $this->assertDatabaseHas('dish_kinds', ['id' => $dishKind->id]);
});
