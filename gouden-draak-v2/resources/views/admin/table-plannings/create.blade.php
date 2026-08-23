@extends('layouts.admin')

@section('page-title', 'New planning')

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

<form method="POST" action="{{ route('admin.table-plannings.store') }}" class="space-y-6 max-w-3xl">
    @csrf

    <div class="rounded-lg border border-slate-200 p-4 space-y-4">
        <div>
            <label for="staff_id" class="block text-sm font-medium text-slate-700">Employee</label>
            <select id="staff_id" name="staff_id" required class="mt-1 block w-full rounded-lg border-slate-300 bg-white px-3 py-2 shadow-sm transition-colors focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900/10">
                <option value="">Select an employee</option>
                @foreach ($staffMembers as $staffMember)
                    <option value="{{ $staffMember->id }}" @selected((int) old('staff_id') === $staffMember->id)>
                        {{ $staffMember->first_name }} {{ $staffMember->last_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="start" class="block text-sm font-medium text-slate-700">Start</label>
                <input
                    id="start"
                    type="datetime-local"
                    name="start"
                    value="{{ old('start') }}"
                    required
                    class="mt-1 block w-full rounded-lg border-slate-300 bg-white px-3 py-2 shadow-sm transition-colors focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900/10"
                >
            </div>

            <div>
                <label for="end" class="block text-sm font-medium text-slate-700">End</label>
                <input
                    id="end"
                    type="datetime-local"
                    name="end"
                    value="{{ old('end') }}"
                    required
                    class="mt-1 block w-full rounded-lg border-slate-300 bg-white px-3 py-2 shadow-sm transition-colors focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900/10"
                >
            </div>
        </div>
        <p class="text-xs text-slate-500">A planning can span a maximum of one week.</p>

        <div>
            <span class="block text-sm font-medium text-slate-700 mb-2">Tables</span>
            <div class="grid grid-cols-3 sm:grid-cols-5 gap-2">
                @foreach ($tables as $table)
                    <label class="flex items-center gap-2 text-sm text-slate-700">
                        <input
                            type="checkbox"
                            name="table_ids[]"
                            value="{{ $table->id }}"
                            @checked(collect(old('table_ids', []))->contains((string) $table->id))
                        >
                        Table {{ $table->nr }}
                    </label>
                @endforeach
            </div>
        </div>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-slate-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-900 focus-visible:ring-offset-2">
            Create planning
        </button>
        <a href="{{ route('admin.table-plannings.index') }}" class="rounded px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">
            Cancel
        </a>
    </div>
</form>
@endsection
