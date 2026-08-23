<?php

use App\Models\Staff;
use App\Models\Table;
use App\Models\TablePlanning;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::create(['name' => 'admin']);

    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin');
});

test('guests cannot access staff management', function () {
    $this->get(route('admin.staff.index'))->assertRedirect('/admin/login');
});

test('an admin can view the staff index', function () {
    $staff = Staff::factory()->create();

    $this->actingAs($this->admin)
        ->get(route('admin.staff.index'))
        ->assertSuccessful()
        ->assertSee($staff->first_name)
        ->assertSee($staff->last_name);
});

test('an admin can create a staff member', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.staff.store'), [
        'first_name' => 'Jan',
        'last_name' => 'Janssen',
    ]);

    $response->assertRedirect(route('admin.staff.index'));

    $this->assertDatabaseHas('staff', [
        'first_name' => 'Jan',
        'last_name' => 'Janssen',
    ]);
});

test('creating a staff member requires a first and last name', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.staff.store'), []);

    $response->assertInvalid(['first_name', 'last_name']);
});

test('an admin can update a staff member', function () {
    $staff = Staff::factory()->create();

    $response = $this->actingAs($this->admin)->put(route('admin.staff.update', $staff), [
        'first_name' => 'Updated',
        'last_name' => 'Name',
    ]);

    $response->assertRedirect(route('admin.staff.index'));

    $this->assertDatabaseHas('staff', [
        'id' => $staff->id,
        'first_name' => 'Updated',
        'last_name' => 'Name',
    ]);
});

test('an admin can delete a staff member', function () {
    $staff = Staff::factory()->create();

    $response = $this->actingAs($this->admin)->delete(route('admin.staff.destroy', $staff));

    $response->assertRedirect(route('admin.staff.index'));
    $this->assertDatabaseMissing('staff', ['id' => $staff->id]);
});

test('the staff edit page shows their table planning entries', function () {
    $staff = Staff::factory()->create();
    $table = Table::factory()->create(['nr' => 7]);

    TablePlanning::query()->create([
        'table_id' => $table->id,
        'staff_id' => $staff->id,
        'start' => '2026-08-20 10:00:00',
        'end' => '2026-08-20 18:00:00',
    ]);

    $this->actingAs($this->admin)
        ->get(route('admin.staff.edit', $staff))
        ->assertSuccessful()
        ->assertSee('Table 7');
});
