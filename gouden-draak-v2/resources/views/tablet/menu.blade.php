@extends('layouts.tablet')

@section('content')
@php
    $pastOrders = $diningSession->orders->sortByDesc('created_at')->map(fn ($order) => [
        'id' => $order->id,
        'placedAt' => $order->created_at->toIso8601String(),
        'items' => $order->items->map(fn ($item) => [
            'dishId' => $item->dish_id,
            'name' => $item->dish->name,
            'quantity' => $item->quantity,
            'notes' => $item->notes,
        ])->values(),
    ])->values();
@endphp

@if ($errors->any())
    <div class="mx-auto max-w-3xl px-4 pt-4">
        <div class="rounded border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            <ul class="list-disc pl-4 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<form method="POST" action="{{ route('tablet.orders.store', $diningSession) }}">
    @csrf

    <div
        id="tablet-order-app"
        data-dish-kinds="{{ json_encode($dishKinds) }}"
        data-table-nr="{{ $diningSession->table->nr }}"
        data-max-rounds="{{ $maxRounds }}"
        data-rounds-remaining="{{ $diningSession->rounds_remaining }}"
        data-next-order-available-at="{{ $diningSession->next_order_available_at?->toIso8601String() }}"
        data-past-orders="{{ json_encode($pastOrders) }}"
        data-status="{{ session('status') }}"
        data-csrf-token="{{ csrf_token() }}"
        data-session-status-url="{{ route('tablet.status', $diningSession) }}"
        data-service-request-action-url="{{ route('tablet.service-requests.store', $diningSession) }}"
        data-service-request-status-url="{{ route('tablet.service-requests.status', $diningSession) }}"
        data-has-pending-service-request="{{ $diningSession->has_pending_service_request ? '1' : '0' }}"
    ></div>
</form>

@vite('resources/js/tablet-order.js')
@endsection
