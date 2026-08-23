<?php

namespace App\Http\Controllers\Tablet;

use App\Actions\Menu\BuildPublicMenu;
use App\Http\Controllers\Controller;
use App\Models\DiningSession;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class MenuController extends Controller
{
    /**
     * Display the tablet ordering page for a dining session.
     */
    public function show(DiningSession $diningSession, BuildPublicMenu $buildPublicMenu): View
    {
        return view('tablet.menu', [
            'diningSession' => $diningSession->load(['table', 'orders.items.dish']),
            'dishKinds' => $buildPublicMenu->handle(),
            'maxRounds' => DiningSession::MAX_ORDERS,
        ]);
    }

    /**
     * Report that the dining session is still active. The route's
     * `dining-session.active` middleware aborts with a 410 before reaching
     * here once the session is closed, so the tablet can poll this to
     * detect a waiter closing the session out from under it.
     */
    public function status(DiningSession $diningSession): JsonResponse
    {
        return response()->json(['active' => true]);
    }
}
