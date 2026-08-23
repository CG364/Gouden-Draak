@extends('layouts.admin')

@section('page-title', 'Table ' . $diningSession->table->nr . ' - tablet link')

@section('content')
<div class="max-w-xl space-y-6">
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-4">
        <div>
            <p class="text-sm text-slate-500">Table</p>
            <p class="text-2xl font-semibold text-slate-900">Table {{ $diningSession->table->nr }}</p>
        </div>

        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-slate-500">Guests</p>
                <p class="text-slate-900">{{ $diningSession->guest_count }} (ages: {{ implode(', ', $diningSession->guest_ages) }})</p>
            </div>
            <div>
                <p class="text-slate-500">Extra Deluxe menu</p>
                <p class="text-slate-900">{{ $diningSession->wants_extra_deluxe_menu ? 'Yes' : 'No' }}</p>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Open this link on the customer's tablet</label>
            <input
                type="text"
                readonly
                value="{{ route('tablet.menu', $diningSession) }}"
                onclick="this.select()"
                class="block w-full rounded border-slate-300 bg-slate-50 font-mono text-sm shadow-sm focus:border-slate-500 focus:ring-slate-500">
        </div>

        <p class="text-xs text-slate-500">
            The tablet keeps working with this link until you close the session below, even if the tablet
            is switched off and back on in between.
        </p>

        <div>
            <button
                type="button"
                id="dining-session-qrcode-toggle"
                class="rounded border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                View QR code
            </button>
            <div
                id="dining-session-qrcode"
                data-url="{{ route('tablet.menu', $diningSession) }}"
                class="hidden mt-3 rounded border border-slate-200 p-3"></div>
        </div>

        <div>
            <a
                href="{{ route('admin.dining-sessions.receipt', $diningSession) }}"
                class="inline-block rounded border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
            >
                Download receipt (PDF)
            </a>
        </div>

        <form method="POST" action="{{ route('admin.dining-sessions.close', $diningSession) }}" onsubmit="return confirm('Close this dining session?');">
            @csrf
            @method('PATCH')
            <button type="submit" class="rounded bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-500">
                Close session
            </button>
        </form>
    </div>

    <a href="{{ route('admin.dining-sessions.index') }}" class="inline-block text-sm text-slate-700 transition-colors hover:text-slate-900 hover:underline">
        &larr; Back to dining sessions
    </a>
</div>

@vite('resources/js/dining-session-qr.js')
@endsection