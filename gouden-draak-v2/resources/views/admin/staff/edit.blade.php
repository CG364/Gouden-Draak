@extends('layouts.admin')

@section('page-title', 'Edit employee')

@section('content')
<form method="POST" action="{{ route('admin.staff.update', $staff) }}" class="space-y-6 max-w-3xl">
    @csrf
    @method('PUT')

    @include('admin.staff._form')

    <div class="flex gap-3">
        <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-slate-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-900 focus-visible:ring-offset-2">
            Save changes
        </button>
        <a href="{{ route('admin.staff.index') }}" class="rounded px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">
            Cancel
        </a>
    </div>
</form>

<div class="mt-10 max-w-3xl">
    <h2 class="text-sm font-semibold text-slate-700 mb-3">Table planning</h2>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr class="transition-colors hover:bg-slate-50">
                    <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">Table</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">Start</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">End</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse ($tablePlannings as $planning)
                    <tr class="transition-colors hover:bg-slate-50">
                        <td class="px-6 py-4 text-sm font-medium text-slate-900">Table {{ $planning->table->nr }}</td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $planning->start->format('d-m-Y H:i') }}</td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $planning->end->format('d-m-Y H:i') }}</td>
                    </tr>
                @empty
                    <tr class="transition-colors hover:bg-slate-50">
                        <td colspan="3" class="px-6 py-4 text-sm text-slate-500">No tables planned for this employee yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <a href="{{ route('admin.table-plannings.create') }}" class="mt-4 inline-block text-sm text-slate-700 underline">
        Create a new planning
    </a>
</div>
@endsection
