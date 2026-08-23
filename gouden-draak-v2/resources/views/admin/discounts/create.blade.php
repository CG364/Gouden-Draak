@extends('layouts.admin')

@section('page-title', 'New discount')

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

<form method="POST" action="{{ route('admin.discounts.store') }}" class="space-y-6 max-w-3xl">
    @csrf

    <div class="rounded-lg border border-slate-200 p-4 space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="starts_at" class="block text-sm font-medium text-slate-700">Starts at</label>
                <input
                    id="starts_at"
                    type="datetime-local"
                    name="starts_at"
                    value="{{ old('starts_at') }}"
                    required
                    class="mt-1 block w-full rounded-lg border-slate-300 bg-white px-3 py-2 shadow-sm transition-colors focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900/10"
                >
            </div>

            <div>
                <label for="ends_at" class="block text-sm font-medium text-slate-700">Ends at</label>
                <input
                    id="ends_at"
                    type="datetime-local"
                    name="ends_at"
                    value="{{ old('ends_at') }}"
                    required
                    class="mt-1 block w-full rounded-lg border-slate-300 bg-white px-3 py-2 shadow-sm transition-colors focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900/10"
                >
            </div>
        </div>
        <p class="text-xs text-slate-500">A discount can span a maximum of one week.</p>

        <div>
            <span class="block text-sm font-medium text-slate-700 mb-2">Products</span>
            <div class="space-y-2">
                @foreach ($dishKinds as $dishKind)
                    @php
                        $groupHasOldSelection = $dishKind->dishes->pluck('id')
                            ->intersect(collect(old('dish_ids', [])))
                            ->isNotEmpty();
                    @endphp
                    <details class="rounded border border-slate-200" @if ($groupHasOldSelection) open @endif>
                        <summary class="cursor-pointer select-none bg-slate-50 px-3 py-2 text-sm font-semibold text-slate-700">
                            {{ $dishKind->name }}
                        </summary>

                        <div class="space-y-2 p-3">
                            @foreach ($dishKind->dishes as $dish)
                                <label class="flex items-center gap-3 text-sm text-slate-700">
                                    <input
                                        type="checkbox"
                                        name="dish_ids[]"
                                        value="{{ $dish->id }}"
                                        @checked(collect(old('dish_ids', []))->contains((string) $dish->id))
                                    >
                                    <span class="w-72">{{ $dish->name }} <span class="text-slate-400">(&euro;{{ number_format((float) $dish->price, 2) }})</span></span>
                                    <input
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        name="discounted_prices[{{ $dish->id }}]"
                                        value="{{ old("discounted_prices.$dish->id") }}"
                                        placeholder="New price"
                                        class="rounded-lg border-slate-300 bg-white px-3 py-2 shadow-sm transition-colors focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900/10 w-32"
                                    >
                                </label>
                            @endforeach
                        </div>
                    </details>
                @endforeach
            </div>
        </div>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-slate-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-900 focus-visible:ring-offset-2">
            Create discount
        </button>
        <a href="{{ route('admin.discounts.index') }}" class="rounded px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">
            Cancel
        </a>
    </div>
</form>
@endsection
