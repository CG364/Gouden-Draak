<?php

namespace App\Http\Controllers\Tablet;

use App\Actions\Orders\PlaceOrder;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tablet\StoreTabletOrderRequest;
use App\Models\DiningSession;
use Illuminate\Http\RedirectResponse;

class OrderController extends Controller
{
    /**
     * Place a new order for a dining session.
     */
    public function store(DiningSession $diningSession, StoreTabletOrderRequest $request, PlaceOrder $placeOrder): RedirectResponse
    {
        $placeOrder->handle($request->validated('quantities'), diningSession: $diningSession, notes: (array) $request->input('notes', []));

        return redirect()->route('tablet.menu', $diningSession)->with('status', 'Order placed.');
    }
}
