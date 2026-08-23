<?php

use App\Models\ServiceRequest;
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

test('guests cannot access waiter call management', function () {
    $this->get(route('admin.service-requests.index'))->assertRedirect('/admin/login');
});

test('a cashier cannot access waiter call management', function () {
    $this->actingAs($this->cashier)
        ->get(route('admin.service-requests.index'))
        ->assertForbidden();
});

test('a waiter sees only unhandled calls, oldest first', function () {
    $table1 = Table::factory()->create(['nr' => 1]);
    $table2 = Table::factory()->create(['nr' => 2]);

    $older = ServiceRequest::factory()->for($table1, 'table')->create(['created_at' => now()->subMinutes(10)]);
    $newer = ServiceRequest::factory()->for($table2, 'table')->create(['created_at' => now()->subMinutes(2)]);
    ServiceRequest::factory()->handled()->create();

    $response = $this->actingAs($this->waiter)->get(route('admin.service-requests.index'));

    $response->assertSuccessful();
    $response->assertSeeInOrder(['Table 1', 'Table 2']);

    $content = $response->getContent();
    expect(substr_count($content, 'Table '))->toBe(2);
});

test('a waiter can mark a call as handled', function () {
    $serviceRequest = ServiceRequest::factory()->create();

    $response = $this->actingAs($this->waiter)->patch(route('admin.service-requests.handle', $serviceRequest));

    $response->assertRedirect(route('admin.service-requests.index'));
    expect($serviceRequest->fresh()->handled)->toBeTrue();
});
