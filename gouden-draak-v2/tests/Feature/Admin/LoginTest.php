<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::create(['name' => 'admin']);
    Role::create(['name' => 'waiter']);
    Role::create(['name' => 'cashier']);
});

test('guests are redirected to the login page when visiting the dashboard', function () {
    $this->get('/admin')->assertRedirect('/admin/login');
});

test('an admin can log in and reach the dashboard', function () {
    $admin = User::factory()->create(['password' => bcrypt('password')]);
    $admin->assignRole('admin');

    $response = $this->post('/admin/login', [
        'email' => $admin->email,
        'password' => 'password',
    ]);

    $response->assertRedirect(route('admin.dashboard'));
    $this->assertAuthenticatedAs($admin);
});

test('a waiter can reach the dashboard', function () {
    $waiter = User::factory()->create(['password' => bcrypt('password')]);
    $waiter->assignRole('waiter');

    $this->actingAs($waiter)->get('/admin')->assertOk();
});

test('a user without any staff role cannot reach the dashboard', function () {
    Role::create(['name' => 'unaffiliated']);

    $user = User::factory()->create(['password' => bcrypt('password')]);
    $user->assignRole('unaffiliated');

    $this->actingAs($user)->get('/admin')->assertForbidden();
});

test('an admin can log out', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $response = $this->actingAs($admin)->post(route('admin.logout'));

    $response->assertRedirect('/admin/login');
    $this->assertGuest();
});
