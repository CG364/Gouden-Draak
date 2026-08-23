@extends('layouts.admin')

@section('page-title', 'Dish kinds')

@section('content')
<div class="mb-4 flex justify-end">
    <a href="{{ route('admin.dish-kinds.create') }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-slate-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-900 focus-visible:ring-offset-2">
        New dish kind
    </a>
</div>

@if (session('error'))
    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
        {{ session('error') }}
    </div>
@endif

<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
            <tr class="transition-colors hover:bg-slate-50">
                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">Name</th>
                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">Dishes</th>
                <th class="px-6 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
            @forelse ($dishKinds as $dishKind)
                <tr class="transition-colors hover:bg-slate-50">
                    <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $dishKind->name }}</td>
                    <td class="px-6 py-4 text-sm text-slate-500">{{ $dishKind->dishes_count }}</td>
                    <td class="px-6 py-4 text-right text-sm space-x-3">
                        <a href="{{ route('admin.dish-kinds.edit', $dishKind) }}" class="text-slate-700 transition-colors hover:text-slate-900 hover:underline">Edit</a>
                        <form method="POST" action="{{ route('admin.dish-kinds.destroy', $dishKind) }}" class="inline" onsubmit="return confirm('Delete this dish kind?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 transition-colors hover:text-red-700 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr class="transition-colors hover:bg-slate-50">
                    <td colspan="3" class="px-6 py-4 text-sm text-slate-500">No dish kinds yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $dishKinds->links() }}
</div>
@endsection
