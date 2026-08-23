@extends('layouts.admin')

@section('page-title', 'Waiter calls')

@section('content')
<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
            <tr class="transition-colors hover:bg-slate-50">
                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">Table</th>
                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">Called at</th>
                <th class="px-6 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
            @forelse ($serviceRequests as $serviceRequest)
                <tr class="transition-colors hover:bg-slate-50">
                    <td class="px-6 py-4 text-sm font-semibold text-slate-900">Table {{ $serviceRequest->table->nr }}</td>
                    <td class="px-6 py-4 text-sm text-slate-500">{{ $serviceRequest->created_at->diffForHumans() }}</td>
                    <td class="px-6 py-4 text-right text-sm">
                        <form method="POST" action="{{ route('admin.service-requests.handle', $serviceRequest) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="rounded bg-slate-900 px-3 py-1 text-sm font-medium text-white hover:bg-slate-700">
                                Mark handled
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr class="transition-colors hover:bg-slate-50">
                    <td colspan="3" class="px-6 py-4 text-sm text-slate-500">No pending waiter calls.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
