@extends('layouts.admin')

@section('page-title', 'New employee')

@section('content')
<form method="POST" action="{{ route('admin.staff.store') }}" class="space-y-6 max-w-3xl">
    @csrf

    @include('admin.staff._form')

    <div class="flex gap-3">
        <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-slate-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-900 focus-visible:ring-offset-2">
            Create employee
        </button>
        <a href="{{ route('admin.staff.index') }}" class="rounded px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">
            Cancel
        </a>
    </div>
</form>
@endsection
