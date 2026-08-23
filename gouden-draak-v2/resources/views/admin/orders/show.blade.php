@extends('layouts.admin')

@section('page-title', 'Order #' . $order->id)

@section('content')
<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden max-w-2xl">
    <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
            <tr class="transition-colors hover:bg-slate-50">
                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">Product</th>
                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">Qty</th>
                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">Unit price</th>
                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">Line total</th>
                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">Notes</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
            @foreach ($order->items as $item)
            <tr class="transition-colors hover:bg-slate-50">
                <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $item->dish->name }}</td>
                <td class="px-6 py-4 text-sm text-slate-500">{{ $item->quantity }}</td>
                <td class="px-6 py-4 text-sm text-slate-500">&euro;{{ number_format((float) $item->unit_price, 2) }}</td>
                <td class="px-6 py-4 text-sm text-slate-500">&euro;{{ number_format((float) $item->unit_price * $item->quantity, 2) }}</td>
                <td class="px-6 py-4 text-sm text-slate-500">{{ $item->notes ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="transition-colors hover:bg-slate-50">
                <td colspan="4" class="px-6 py-4 text-right text-sm font-bold text-slate-900">Total</td>
                <td class="px-6 py-4 text-sm font-bold text-slate-900">&euro;{{ number_format((float) $order->total, 2) }}</td>
            </tr>
        </tfoot>
    </table>
</div>

<p class="mt-4 text-sm text-slate-500">
    @if ($order->placedBy)
    Placed by {{ $order->placedBy->name }}
    @elseif ($order->customer_name)
    Takeout order for {{ $order->customer_name }}
    @elseif ($order->diningSession)
    Placed by Table {{ $order->diningSession->table->nr }}
    @else
    Placed by a customer
    @endif
    on {{ $order->created_at->format('d-m-Y H:i') }}
</p>

<a href="{{ route('admin.orders.index') }}" class="mt-4 inline-block text-sm text-slate-700 underline">
    Back to orders
</a>
@endsection