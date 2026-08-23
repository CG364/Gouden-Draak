@extends('main.layout')

@section('content')
<div class="bg-white font-[sans-serrif] border border-black p-6 text-center">
    <p class="text-xl font-bold">{{ __('order.confirmation_title', ['name' => $order->customer_name]) }}</p>
    <p class="mt-1 font-bold">{{ __('order.confirmation_order_number', ['number' => $order->id]) }}</p>
    <p class="mt-2 mx-auto max-w-md text-sm text-gray-600">{{ __('order.confirmation_instructions') }}</p>

    <div class="mt-6 flex justify-center">
        <div id="order-confirmation-qr" data-qr-text="{{ $qrText }}"></div>
    </div>

    <table class="mt-6 mx-auto w-full max-w-md text-left text-sm">
        <thead>
            <tr class="border-b border-gray-300">
                <th class="py-2">#</th>
                <th class="py-2">{{ __('order.dish_column') }}</th>
                <th class="py-2 text-right">{{ __('order.quantity_column') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr class="border-b border-gray-100">
                    <td class="py-2">{{ $item->dish->menu_number }}</td>
                    <td class="py-2">
                        {{ $item->dish->name }}
                        @if ($item->notes)
                            <span class="block text-xs text-gray-500">{{ $item->notes }}</span>
                        @endif
                    </td>
                    <td class="py-2 text-right">{{ $item->quantity }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" class="py-2 text-right font-bold">{{ __('order.total_label') }}</td>
                <td class="py-2 text-right font-bold">&euro; {{ number_format((float) $order->total, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <button
        type="button"
        onclick="window.print()"
        class="mt-6 rounded bg-gray-900 px-4 py-2 text-sm font-medium text-white print:hidden"
    >
        {{ __('order.print') }}
    </button>
</div>

@vite('resources/js/order-confirmation-qr.js')
@endsection
