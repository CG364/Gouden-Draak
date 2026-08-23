<?php

namespace App\Http\Controllers\Tablet;

use App\Actions\ServiceRequests\CallWaiter;
use App\Http\Controllers\Controller;
use App\Models\DiningSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ServiceRequestController extends Controller
{
    /**
     * Call a waiter to the dining session's table.
     *
     * The tablet calls this via fetch (Accept: application/json) so it can
     * report the call without a full page reload, which would otherwise
     * discard whatever order the customer is still assembling in the cart.
     */
    public function store(DiningSession $diningSession, CallWaiter $callWaiter, Request $request): RedirectResponse|JsonResponse
    {
        $callWaiter->handle($diningSession);

        if ($request->wantsJson()) {
            return response()->json(['status' => 'A waiter has been called to your table.']);
        }

        return redirect()->route('tablet.menu', $diningSession)->with('status', 'A waiter has been called to your table.');
    }

    /**
     * Report whether the dining session still has an unhandled waiter call,
     * so the tablet can poll and clear the "waiter called" state on its own
     * once a waiter marks it handled, without the customer refreshing.
     */
    public function status(DiningSession $diningSession): JsonResponse
    {
        return response()->json([
            'hasPendingServiceRequest' => $diningSession->has_pending_service_request,
        ]);
    }
}
