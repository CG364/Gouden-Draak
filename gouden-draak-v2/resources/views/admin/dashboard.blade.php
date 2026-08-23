@extends('layouts.admin')

@section('page-title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    @if ($isAdmin)
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <p class="text-sm text-slate-500">Pages</p>
            <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $pageCount }}</p>
            <a href="{{ route('admin.pages.index') }}" class="mt-4 inline-block text-sm text-slate-700 underline">
                Manage pages
            </a>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <p class="text-sm text-slate-500">Dish kinds</p>
            <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $dishKindCount }}</p>
            <a href="{{ route('admin.dish-kinds.index') }}" class="mt-4 inline-block text-sm text-slate-700 underline">
                Manage dish kinds
            </a>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <p class="text-sm text-slate-500">Dishes</p>
            <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $dishCount }}</p>
            <a href="{{ route('admin.dishes.index') }}" class="mt-4 inline-block text-sm text-slate-700 underline">
                Manage dishes
            </a>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <p class="text-sm text-slate-500">Employees</p>
            <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $staffCount }}</p>
            <a href="{{ route('admin.staff.index') }}" class="mt-4 inline-block text-sm text-slate-700 underline">
                Manage employees
            </a>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <p class="text-sm text-slate-500">Table planning entries</p>
            <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $tablePlanningCount }}</p>
            <a href="{{ route('admin.table-plannings.index') }}" class="mt-4 inline-block text-sm text-slate-700 underline">
                Manage table planning
            </a>
        </div>
    @endif

    @if ($isAdmin || $isCashier)
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <p class="text-sm text-slate-500">Discounts</p>
            <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $discountCount }}</p>
            <a href="{{ route('admin.discounts.index') }}" class="mt-4 inline-block text-sm text-slate-700 underline">
                Manage discounts
            </a>
        </div>
    @endif

    @if ($isAdmin || $isCashier)
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <p class="text-sm text-slate-500">Orders</p>
            <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $orderCount }}</p>
            <a href="{{ route('admin.orders.index') }}" class="mt-4 inline-block text-sm text-slate-700 underline">
                Manage orders
            </a>
        </div>
    @endif

    @if ($isAdmin || $isWaiter)
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <p class="text-sm text-slate-500">Active dining sessions</p>
            <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $activeDiningSessionCount }}</p>
            <a href="{{ route('admin.dining-sessions.index') }}" class="mt-4 inline-block text-sm text-slate-700 underline">
                Manage dining sessions
            </a>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <p class="text-sm text-slate-500">Pending waiter calls</p>
            <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $unhandledServiceRequestCount }}</p>
            <a href="{{ route('admin.service-requests.index') }}" class="mt-4 inline-block text-sm text-slate-700 underline">
                View waiter calls
            </a>
        </div>
    @endif
</div>
@endsection
