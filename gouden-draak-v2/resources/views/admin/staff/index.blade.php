@extends('layouts.admin')

@section('page-title', 'Employees')

@section('content')
<div class="mb-4 flex justify-end">
    <a href="{{ route('admin.staff.create') }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-slate-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-900 focus-visible:ring-offset-2">
        New employee
    </a>
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
            <tr class="transition-colors hover:bg-slate-50">
                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">First name</th>
                <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-slate-500 uppercase">Last name</th>
                <th class="px-6 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
            @forelse ($staffMembers as $staffMember)
                <tr class="transition-colors hover:bg-slate-50">
                    <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $staffMember->first_name }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $staffMember->last_name }}</td>
                    <td class="px-6 py-4 text-right text-sm space-x-3">
                        <a href="{{ route('admin.staff.edit', $staffMember) }}" class="text-slate-700 transition-colors hover:text-slate-900 hover:underline">Edit</a>
                        <form method="POST" action="{{ route('admin.staff.destroy', $staffMember) }}" class="inline" onsubmit="return confirm('Delete this employee?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 transition-colors hover:text-red-700 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr class="transition-colors hover:bg-slate-50">
                    <td colspan="3" class="px-6 py-4 text-sm text-slate-500">No employees yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $staffMembers->links() }}
</div>
@endsection
