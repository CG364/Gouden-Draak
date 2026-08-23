<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Orders\ListOrderableDishes;
use App\Actions\Orders\PlaceOrder;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreOrderRequest;
use App\Models\DishKind;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index(): View
    {
        return view('admin.orders.index', [
            'orders' => Order::query()->with(['placedBy', 'items', 'diningSession.table'])->orderByDesc('created_at')->paginate(15)->onEachSide(1),
        ]);
    }

    /**
     * Show the form for assembling a new order.
     */
    public function create(ListOrderableDishes $listOrderableDishes): View
    {
        return view('admin.orders.create', [
            'dishes' => $listOrderableDishes->handle(),
            'dishKinds' => DishKind::query()->orderBy('id')->get()->map(fn (DishKind $dishKind) => [
                'id' => $dishKind->id,
                'name' => $dishKind->name,
            ])->values(),
        ]);
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(StoreOrderRequest $request, PlaceOrder $placeOrder): RedirectResponse
    {
        $placeOrder->handle($request->validated()['quantities'], $request->user(), notes: (array) $request->input('notes', []));

        return redirect()->route('admin.orders.index')->with('status', 'Order placed.');
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order): View
    {
        return view('admin.orders.show', [
            'order' => $order->load(['items.dish', 'placedBy', 'diningSession.table']),
        ]);
    }

    /**
     * Remove the specified order from storage.
     */
    public function destroy(Order $order): RedirectResponse
    {
        $order->delete();

        return redirect()->route('admin.orders.index')->with('status', 'Order deleted.');
    }
}
