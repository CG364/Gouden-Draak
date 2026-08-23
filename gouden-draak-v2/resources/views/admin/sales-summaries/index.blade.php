@extends('layouts.admin')

@section('page-title', 'Sales summaries')

@section('content')
<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
            <tr class="transition-colors hover:bg-slate-50">
                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">Date</th>
                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">Orders</th>
                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">Revenue</th>
                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">Generated</th>
                <th class="px-6 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
            @forelse ($salesSummaries as $salesSummary)
            <tr class="transition-colors hover:bg-slate-50">
                <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $salesSummary->date->format('d-m-Y') }}</td>
                <td class="px-6 py-4 text-sm text-slate-500">{{ $salesSummary->total_orders }}</td>
                <td class="px-6 py-4 text-sm text-slate-500">&euro;{{ number_format((float) $salesSummary->total_revenue, 2, ',', '.') }}</td>
                <td class="px-6 py-4 text-sm text-slate-500">{{ $salesSummary->created_at->format('d-m-Y H:i') }}</td>
                <td class="px-6 py-4 text-right text-sm">
                    <a href="{{ route('admin.sales-summaries.download', $salesSummary) }}" class="text-slate-700 transition-colors hover:text-slate-900 hover:underline">
                        Download
                    </a>
                </td>
            </tr>
            @empty
            <tr class="transition-colors hover:bg-slate-50">
                <td colspan="5" class="px-6 py-4 text-sm text-slate-500">No sales summaries have been generated yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $salesSummaries->links() }}
</div>
@endsection
