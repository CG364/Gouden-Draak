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

test('guests cannot access table planning management', function () {
    $this->get(route('admin.table-plannings.index'))->assertRedirect('/admin/login');
});

test('an admin can view the table planning index', function () {
    $staff = Staff::factory()->create();
    $table = Table::factory()->create(['nr' => 3]);

    TablePlanning::query()->create([
        'table_id' => $table->id,
        'staff_id' => $staff->id,
        'start' => '2026-08-20 10:00:00',
        'end' => '2026-08-20 18:00:00',
    ]);

    $this->actingAs($this->admin)
        ->get(route('admin.table-plannings.index'))
        ->assertSuccessful()
        ->assertSee($staff->first_name)
        ->assertSee('Table 3');
});

test('an admin can create a planning that assigns a staff member to multiple tables', function () {
    $staff = Staff::factory()->create();
    $tableOne = Table::factory()->create();
    $tableTwo = Table::factory()->create();

    $response = $this->actingAs($this->admin)->post(route('admin.table-plannings.store'), [
        'staff_id' => $staff->id,
        'table_ids' => [$tableOne->id, $tableTwo->id],
        'start' => '2026-08-20T10:00',
        'end' => '2026-08-21T18:00',
    ]);

    $response->assertRedirect(route('admin.table-plannings.index'));

    $this->assertDatabaseHas('table_plannings', ['table_id' => $tableOne->id, 'staff_id' => $staff->id]);
    $this->assertDatabaseHas('table_plannings', ['table_id' => $tableTwo->id, 'staff_id' => $staff->id]);
});

test('a planning cannot span more than one week', function () {
    $staff = Staff::factory()->create();
    $table = Table::factory()->create();

    $response = $this->actingAs($this->admin)->post(route('admin.table-plannings.store'), [
        'staff_id' => $staff->id,
        'table_ids' => [$table->id],
        'start' => '2026-08-20T10:00',
        'end' => '2026-08-28T10:00',
    ]);

    $response->assertInvalid(['end']);
});

test('creating a planning requires at least one table', function () {
    $staff = Staff::factory()->create();

    $response = $this->actingAs($this->admin)->post(route('admin.table-plannings.store'), [
        'staff_id' => $staff->id,
        'table_ids' => [],
        'start' => '2026-08-20T10:00',
        'end' => '2026-08-20T18:00',
    ]);

    $response->assertInvalid(['table_ids']);
});

test('an admin can delete a table planning entry', function () {
    $staff = Staff::factory()->create();
    $table = Table::factory()->create();

    $planning = TablePlanning::query()->create([
        'table_id' => $table->id,
        'staff_id' => $staff->id,
        'start' => '2026-08-20 10:00:00',
        'end' => '2026-08-20 18:00:00',
    ]);

    $response = $this->actingAs($this->admin)->delete(route('admin.table-plannings.destroy', $planning));

    $response->assertRedirect(route('admin.table-plannings.index'));
    $this->assertDatabaseMissing('table_plannings', ['id' => $planning->id]);
});
