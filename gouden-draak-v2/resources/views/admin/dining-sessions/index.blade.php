@extends('layouts.admin')

@section('page-title', 'Dining sessions')

@section('content')
<div class="mb-4 flex justify-end">
    <a href="{{ route('admin.dining-sessions.create') }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-slate-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-900 focus-visible:ring-offset-2">
        Start new session
    </a>
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-8">
    <div class="px-6 py-3 bg-slate-50 border-b border-slate-200">
        <h2 class="text-xs font-medium text-slate-500 uppercase">Active sessions</h2>
    </div>
    <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
            <tr class="transition-colors hover:bg-slate-50">
                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">Table</th>
                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">Guests</th>
                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">Opened by</th>
                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">Started</th>
                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">Rounds ordered</th>
                <th class="px-6 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
            @forelse ($activeDiningSessions as $diningSession)
            <tr class="transition-colors hover:bg-slate-50">
                <td class="px-6 py-4 text-sm font-medium text-slate-900">Table {{ $diningSession->table->nr }}</td>
                <td class="px-6 py-4 text-sm text-slate-500">{{ $diningSession->guest_count }}</td>
                <td class="px-6 py-4 text-sm text-slate-500">{{ $diningSession->openedBy?->name ?? '-' }}</td>
                <td class="px-6 py-4 text-sm text-slate-500">{{ $diningSession->started_at->format('d-m-Y H:i') }}</td>
                <td class="px-6 py-4 text-sm text-slate-500">{{ $diningSession->orders_count }} / {{ $maxOrders }}</td>
                <td class="px-6 py-4 text-right text-sm space-x-3">
                    <a href="{{ route('admin.dining-sessions.show', $diningSession) }}" class="text-slate-700 transition-colors hover:text-slate-900 hover:underline">
                        Tablet link
                    </a>
                    <form method="POST" action="{{ route('admin.dining-sessions.close', $diningSession) }}" class="inline" onsubmit="return confirm('Close this dining session?');">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="text-red-600 transition-colors hover:text-red-700 hover:underline">Close</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr class="transition-colors hover:bg-slate-50">
                <td colspan="6" class="px-6 py-4 text-sm text-slate-500">No active dining sessions.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-6 py-3 bg-slate-50 border-b border-slate-200">
        <h2 class="text-xs font-medium text-slate-500 uppercase">Closed sessions</h2>
    </div>
    <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
            <tr class="transition-colors hover:bg-slate-50">
                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">Table</th>
                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">Guests</th>
                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">Opened by</th>
                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">Started</th>
                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">Ended</th>
                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">Rounds ordered</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
            @forelse ($closedDiningSessions as $diningSession)
            <tr class="transition-colors hover:bg-slate-50">
                <td class="px-6 py-4 text-sm font-medium text-slate-900">Table {{ $diningSession->table->nr }}</td>
                <td class="px-6 py-4 text-sm text-slate-500">{{ $diningSession->guest_count }}</td>
                <td class="px-6 py-4 text-sm text-slate-500">{{ $diningSession->openedBy?->name ?? '-' }}</td>
                <td class="px-6 py-4 text-sm text-slate-500">{{ $diningSession->started_at->format('d-m-Y H:i') }}</td>
                <td class="px-6 py-4 text-sm text-slate-500">{{ $diningSession->ended_at->format('d-m-Y H:i') }}</td>
                <td class="px-6 py-4 text-sm text-slate-500">{{ $diningSession->orders_count }} / {{ $maxOrders }}</td>
            </tr>
            @empty
            <tr class="transition-colors hover:bg-slate-50">
                <td colspan="6" class="px-6 py-4 text-sm text-slate-500">No closed dining sessions yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $closedDiningSessions->links() }}
</div>
@endsection