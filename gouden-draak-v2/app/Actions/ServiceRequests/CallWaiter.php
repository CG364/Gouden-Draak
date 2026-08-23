<?php

namespace App\Actions\ServiceRequests;

use App\Models\DiningSession;
use App\Models\ServiceRequest;

class CallWaiter
{
    /**
     * Call a waiter to a dining session's table. Returns the existing
     * unhandled request instead of creating a new one if the customer taps
     * the button more than once before a waiter gets to the table.
     */
    public function handle(DiningSession $diningSession): ServiceRequest
    {
        $pendingRequest = $diningSession->serviceRequests()->unhandled()->first();

        if ($pendingRequest !== null) {
            return $pendingRequest;
        }

        return ServiceRequest::query()->create([
            'table_id' => $diningSession->table_id,
            'dining_session_id' => $diningSession->id,
            'handled' => false,
        ]);
    }
}
