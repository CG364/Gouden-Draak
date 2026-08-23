<?php

namespace App\Http\Controllers;

use App\Actions\Menu\BuildPublicMenu;
use App\Actions\Orders\BuildOrderQrText;
use App\Actions\Orders\PlaceOrder;
use App\Http\Requests\StoreTakeoutOrderRequest;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TakeoutOrderController extends Controller
{
    /**
     * Show the takeout ordering page.
     */
    public function create(BuildPublicMenu $buildPublicMenu): View
    {
        return view('main.order.create', [
            'dishKinds' => $buildPublicMenu->handle(),
        ]);
    }

    /**
     * Place a new takeout order and send the customer to their confirmation page.
     */
    public function store(StoreTakeoutOrderRequest $request, PlaceOrder $placeOrder): RedirectResponse
    {
        $order = $placeOrder->handle(
            $request->validated('quantities'),
            customerName: $request->validated('customer_name'),
            notes: (array) $request->input('notes', []),
        );

        return redirect()->route('order.show', ['order' => $order->token]);
    }

    /**
     * Show a takeout order's confirmation page, including its QR code.
     */
    public function show(Order $order, BuildOrderQrText $buildOrderQrText): View
    {
        $order->load('items.dish');

        return view('main.order.confirmation', [
            'order' => $order,
            'qrText' => $buildOrderQrText->handle($order),
        ]);
    }
}
