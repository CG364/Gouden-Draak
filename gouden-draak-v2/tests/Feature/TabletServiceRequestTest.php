<?php

use App\Models\DiningSession;
use App\Models\ServiceRequest;
use App\Models\Table;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('a dining session can call a waiter', function () {
    $table = Table::factory()->create();
    $diningSession = DiningSession::factory()->for($table, 'table')->create();

    $response = $this->post(route('tablet.service-requests.store', $diningSession));

    $response->assertRedirect(route('tablet.menu', $diningSession));

    $this->assertDatabaseHas('service_requests', [
        'table_id' => $table->id,
        'dining_session_id' => $diningSession->id,
        'handled' => false,
    ]);
});

test('calling the waiter twice does not create a duplicate unhandled request', function () {
    $diningSession = DiningSession::factory()->create();

    $this->post(route('tablet.service-requests.store', $diningSession));
    $this->post(route('tablet.service-requests.store', $diningSession));

    expect(ServiceRequest::query()->where('dining_session_id', $diningSession->id)->count())->toBe(1);
});

test('the tablet menu shows the call has been made after calling the waiter', function () {
    $diningSession = DiningSession::factory()->create();

    $this->post(route('tablet.service-requests.store', $diningSession));

    $response = $this->get(route('tablet.menu', $diningSession));

    $response->assertSuccessful();
    $response->assertSee('data-has-pending-service-request="1"', escape: false);
});

test('calling the waiter via ajax returns a json status instead of redirecting', function () {
    $diningSession = DiningSession::factory()->create();

    $response = $this->postJson(route('tablet.service-requests.store', $diningSession));

    $response->assertSuccessful();
    $response->assertJson(['status' => 'A waiter has been called to your table.']);

    $this->assertDatabaseHas('service_requests', [
        'dining_session_id' => $diningSession->id,
        'handled' => false,
    ]);
});

test('a closed dining session cannot call a waiter', function () {
    $diningSession = DiningSession::factory()->closed()->create();

    $this->post(route('tablet.service-requests.store', $diningSession))->assertStatus(410);
});

test('the status endpoint reports whether a call is still pending', function () {
    $diningSession = DiningSession::factory()->create();

    $this->getJson(route('tablet.service-requests.status', $diningSession))
        ->assertSuccessful()
        ->assertJson(['hasPendingServiceRequest' => false]);

    $serviceRequest = ServiceRequest::factory()->create([
        'table_id' => $diningSession->table_id,
        'dining_session_id' => $diningSession->id,
    ]);

    $this->getJson(route('tablet.service-requests.status', $diningSession))
        ->assertSuccessful()
        ->assertJson(['hasPendingServiceRequest' => true]);

    $serviceRequest->update(['handled' => true]);

    $this->getJson(route('tablet.service-requests.status', $diningSession))
        ->assertSuccessful()
        ->assertJson(['hasPendingServiceRequest' => false]);
});
