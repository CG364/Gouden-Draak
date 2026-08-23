@extends('layouts.admin')

@section('page-title', 'Orders')

@section('content')
<div class="mb-4 flex justify-end">
    <a href="{{ route('admin.orders.create') }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-slate-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-900 focus-visible:ring-offset-2">
        New order
    </a>
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
            <tr class="transition-colors hover:bg-slate-50">
                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">#</th>
                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">Placed by</th>
                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">Items</th>
                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">Total</th>
                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">Placed at</th>
                <th class="px-6 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
            @forelse ($orders as $order)
            <tr class="transition-colors hover:bg-slate-50">
                <td class="px-6 py-4 text-sm text-slate-500">{{ $order->id }}</td>
                <td class="px-6 py-4 text-sm font-medium text-slate-900">
                    @if ($order->placedBy)
                    {{ $order->placedBy->name }}
                    @elseif ($order->customer_name)
                    {{ $order->customer_name }} <span class="text-xs text-slate-400">(Takeout)</span>
                    @elseif ($order->diningSession)
                    Table {{ $order->diningSession->table->nr }}
                    @else
                    -
                    @endif
                </td>
                <td class="px-6 py-4 text-sm text-slate-500">{{ $order->items->sum('quantity') }}</td>
                <td class="px-6 py-4 text-sm text-slate-500">&euro;{{ number_format((float) $order->total, 2) }}</td>
                <td class="px-6 py-4 text-sm text-slate-500">{{ $order->created_at->format('d-m-Y H:i') }}</td>
                <td class="px-6 py-4 text-right text-sm space-x-3">
                    <a href="{{ route('admin.orders.show', $order) }}" class="text-slate-700 transition-colors hover:text-slate-900 hover:underline">View</a>
                    <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" class="inline" onsubmit="return confirm('Delete this order?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 transition-colors hover:text-red-700 hover:underline">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr class="transition-colors hover:bg-slate-50">
                <td colspan="6" class="px-6 py-4 text-sm text-slate-500">No orders yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $orders->links() }}
</div>
@endsection