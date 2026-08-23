@extends('layouts.admin')

@section('page-title', 'Pages')

@section('content')
<div class="mb-4 flex justify-end">
    <a href="{{ route('admin.pages.create') }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-slate-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-900 focus-visible:ring-offset-2">
        New page
    </a>
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
            <tr class="transition-colors hover:bg-slate-50">
                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">Title</th>
                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">Slug</th>
                <th class="px-6 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
            @forelse ($pages as $page)
                <tr class="transition-colors hover:bg-slate-50">
                    <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $page->title }}</td>
                    <td class="px-6 py-4 text-sm text-slate-500">{{ $page->slug }}</td>
                    <td class="px-6 py-4 text-right text-sm space-x-3">
                        <a href="{{ route('pages.show', $page) }}" target="_blank" class="text-slate-500 transition-colors hover:text-slate-700 hover:underline">View</a>
                        <a href="{{ route('admin.pages.edit', $page) }}" class="text-slate-700 transition-colors hover:text-slate-900 hover:underline">Edit</a>
                        <form method="POST" action="{{ route('admin.pages.destroy', $page) }}" class="inline" onsubmit="return confirm('Delete this page?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 transition-colors hover:text-red-700 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr class="transition-colors hover:bg-slate-50">
                    <td colspan="3" class="px-6 py-4 text-sm text-slate-500">No pages yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $pages->links() }}
</div>
@endsection
