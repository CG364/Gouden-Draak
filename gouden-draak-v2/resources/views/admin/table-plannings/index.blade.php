@extends('layouts.admin')

@section('page-title', 'Table planning')

@section('content')
<div class="mb-4 flex justify-end">
    <a href="{{ route('admin.table-plannings.create') }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-slate-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-900 focus-visible:ring-offset-2">
        New planning
    </a>
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
            <tr class="transition-colors hover:bg-slate-50">
                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">Employee</th>
                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">Table</th>
                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">Start</th>
                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">End</th>
                <th class="px-6 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
            @forelse ($tablePlannings as $planning)
                <tr class="transition-colors hover:bg-slate-50">
                    <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $planning->staff->first_name }} {{ $planning->staff->last_name }}</td>
                    <td class="px-6 py-4 text-sm text-slate-500">Table {{ $planning->table->nr }}</td>
                    <td class="px-6 py-4 text-sm text-slate-500">{{ $planning->start->format('d-m-Y H:i') }}</td>
                    <td class="px-6 py-4 text-sm text-slate-500">{{ $planning->end->format('d-m-Y H:i') }}</td>
                    <td class="px-6 py-4 text-right text-sm">
                        <form method="POST" action="{{ route('admin.table-plannings.destroy', $planning) }}" class="inline" onsubmit="return confirm('Delete this planning entry?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 transition-colors hover:text-red-700 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr class="transition-colors hover:bg-slate-50">
                    <td colspan="5" class="px-6 py-4 text-sm text-slate-500">No planning entries yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $tablePlannings->links() }}
</div>
@endsection
