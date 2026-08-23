@extends('layouts.admin')

@section('page-title', 'Navbar')

@section('content')
<p class="mb-4 text-sm text-slate-500">
    Controls the links shown in the navigation bar on the public website, in this order.
</p>

<div class="mb-4 flex justify-end">
    <a href="{{ route('admin.navbar-items.create') }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-slate-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-900 focus-visible:ring-offset-2">
        New navbar item
    </a>
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
            <tr class="transition-colors hover:bg-slate-50">
                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">Label</th>
                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">Links to</th>
                <th class="px-6 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
            @forelse ($navbarItems as $navbarItem)
                <tr class="transition-colors hover:bg-slate-50">
                    <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $navbarItem->header }}</td>
                    <td class="px-6 py-4 text-sm text-slate-500">
                        @if ($navbarItem->page)
                            Page: {{ $navbarItem->page->title }}
                        @else
                            {{ $navbarItem->foreign_url }}
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right text-sm space-x-3">
                        <form method="POST" action="{{ route('admin.navbar-items.move-up', $navbarItem) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-slate-500 transition-colors hover:text-slate-700 hover:underline disabled:cursor-not-allowed disabled:opacity-30" @disabled($loop->first)>
                                &uarr;
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.navbar-items.move-down', $navbarItem) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-slate-500 transition-colors hover:text-slate-700 hover:underline disabled:cursor-not-allowed disabled:opacity-30" @disabled($loop->last)>
                                &darr;
                            </button>
                        </form>
                        <a href="{{ route('admin.navbar-items.edit', $navbarItem) }}" class="text-slate-700 transition-colors hover:text-slate-900 hover:underline">Edit</a>
                        <form method="POST" action="{{ route('admin.navbar-items.destroy', $navbarItem) }}" class="inline" onsubmit="return confirm('Delete this navbar item?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 transition-colors hover:text-red-700 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr class="transition-colors hover:bg-slate-50">
                    <td colspan="3" class="px-6 py-4 text-sm text-slate-500">No navbar items yet. The public site will show no navigation links until you add some.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
