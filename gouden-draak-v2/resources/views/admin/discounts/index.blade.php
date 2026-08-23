@extends('layouts.admin')

@section('page-title', 'Discounts')

@section('content')
<div class="mb-4 flex justify-end">
    <a href="{{ route('admin.discounts.create') }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-slate-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-900 focus-visible:ring-offset-2">
        New discount
    </a>
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
            <tr class="transition-colors hover:bg-slate-50">
                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">Products</th>
                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">Starts</th>
                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">Ends</th>
                <th class="px-6 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
            @forelse ($discounts as $discount)
            <tr class="transition-colors hover:bg-slate-50">
                <td class="px-6 py-4 text-sm font-medium text-slate-900">
                    <ul class="space-y-1">
                        @foreach ($discount->dishes as $dish)
                        <li>{{ $dish->name }} - &euro;{{ number_format((float) $dish->pivot->discounted_price, 2) }}</li>
                        @endforeach
                    </ul>
                </td>
                <td class="px-6 py-4 text-sm text-slate-500">{{ $discount->starts_at->format('d-m-Y H:i') }}</td>
                <td class="px-6 py-4 text-sm text-slate-500">{{ $discount->ends_at->format('d-m-Y H:i') }}</td>
                <td class="px-6 py-4 text-right text-sm">
                    <form method="POST" action="{{ route('admin.discounts.destroy', $discount) }}" class="inline" onsubmit="return confirm('Delete this discount?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 transition-colors hover:text-red-700 hover:underline">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr class="transition-colors hover:bg-slate-50">
                <td colspan="4" class="px-6 py-4 text-sm text-slate-500">No discounts yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $discounts->links() }}
</div>
@endsection