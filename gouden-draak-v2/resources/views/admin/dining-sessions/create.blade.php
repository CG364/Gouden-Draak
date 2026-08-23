@extends('layouts.admin')

@section('page-title', 'Start new session')

@section('content')
@if ($errors->any())
    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
        <ul class="list-disc pl-4 space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('admin.dining-sessions.store') }}" class="space-y-6 max-w-md">
    @csrf

    <div class="rounded-lg border border-slate-200 p-4 space-y-4">
        <div>
            <label for="table_id" class="block text-sm font-medium text-slate-700">Table</label>
            <select id="table_id" name="table_id" required class="mt-1 block w-full rounded-lg border-slate-300 bg-white px-3 py-2 shadow-sm transition-colors focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900/10">
                <option value="">Select a free table</option>
                @foreach ($tables as $table)
                    <option value="{{ $table->id }}" @selected((int) old('table_id') === $table->id)>
                        Table {{ $table->nr }}
                    </option>
                @endforeach
            </select>

            @if ($tables->isEmpty())
                <p class="mt-2 text-xs text-slate-500">Every table already has an active dining session.</p>
            @endif
        </div>

        <div>
            <label for="guest_count" class="block text-sm font-medium text-slate-700">Number of guests</label>
            <input
                type="number"
                id="guest_count"
                name="guest_count"
                min="1"
                max="{{ $maxGuests }}"
                value="{{ old('guest_count') }}"
                required
                class="mt-1 block w-full rounded-lg border-slate-300 bg-white px-3 py-2 shadow-sm transition-colors focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900/10"
            >
            <p class="mt-1 text-xs text-slate-500">Maximum {{ $maxGuests }} guests per table.</p>
        </div>

        <div>
            <label for="guest_ages" class="block text-sm font-medium text-slate-700">Ages of the guests</label>
            <input
                type="text"
                id="guest_ages"
                name="guest_ages"
                placeholder="e.g. 34, 36, 8, 10"
                value="{{ old('guest_ages') }}"
                required
                class="mt-1 block w-full rounded-lg border-slate-300 bg-white px-3 py-2 shadow-sm transition-colors focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900/10"
            >
            <p class="mt-1 text-xs text-slate-500">Comma-separated, one age per guest.</p>
        </div>

        <div>
            <span class="block text-sm font-medium text-slate-700">Extra Deluxe menu?</span>
            <div class="mt-1 flex gap-4">
                <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                    <input type="radio" name="wants_extra_deluxe_menu" value="1" @checked(old('wants_extra_deluxe_menu') == '1') required>
                    Yes
                </label>
                <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                    <input type="radio" name="wants_extra_deluxe_menu" value="0" @checked(old('wants_extra_deluxe_menu') == '0') required>
                    No
                </label>
            </div>
        </div>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-slate-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-900 focus-visible:ring-offset-2">
            Start session
        </button>
        <a href="{{ route('admin.dining-sessions.index') }}" class="rounded px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">
            Cancel
        </a>
    </div>
</form>
@endsection
