@extends('layouts.admin')

@section('page-title', 'Dishes')

@section('content')
<div class="mb-4 flex justify-end">
    <a href="{{ route('admin.dishes.create') }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-slate-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-900 focus-visible:ring-offset-2">
        New dish
    </a>
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
            <tr class="transition-colors hover:bg-slate-50">
                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">#</th>
                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">Name</th>
                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">Dish kind</th>
                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">Price</th>
                <th class="px-6 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
            @forelse ($dishes as $dish)
                <tr class="transition-colors hover:bg-slate-50">
                    <td class="px-6 py-4 text-sm text-slate-500">{{ $dish->menu_number }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $dish->name }}</td>
                    <td class="px-6 py-4 text-sm text-slate-500">{{ $dish->dishKind?->name }}</td>
                    <td class="px-6 py-4 text-sm text-slate-500">&euro;{{ number_format((float) $dish->price, 2) }}</td>
                    <td class="px-6 py-4 text-right text-sm space-x-3">
                        <a href="{{ route('admin.dishes.edit', $dish) }}" class="text-slate-700 transition-colors hover:text-slate-900 hover:underline">Edit</a>
                        <form method="POST" action="{{ route('admin.dishes.destroy', $dish) }}" class="inline" onsubmit="return confirm('Delete this dish?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 transition-colors hover:text-red-700 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr class="transition-colors hover:bg-slate-50">
                    <td colspan="5" class="px-6 py-4 text-sm text-slate-500">No dishes yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $dishes->links() }}
</div>
@endsection
