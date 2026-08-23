@php
    $isAdmin = auth()->user()?->hasRole('admin') ?? false;
    $isCashier = auth()->user()?->hasRole('cashier') ?? false;
    $isWaiter = auth()->user()?->hasRole('waiter') ?? false;

    $navItems = [
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'active' => 'admin.dashboard'],
    ];

    if ($isAdmin) {
        $navItems[] = ['label' => 'Pages', 'route' => 'admin.pages.index', 'active' => 'admin.pages.*'];
        $navItems[] = ['label' => 'Dish kinds', 'route' => 'admin.dish-kinds.index', 'active' => 'admin.dish-kinds.*'];
        $navItems[] = ['label' => 'Dishes', 'route' => 'admin.dishes.index', 'active' => 'admin.dishes.*'];
        $navItems[] = ['label' => 'Employees', 'route' => 'admin.staff.index', 'active' => 'admin.staff.*'];
        $navItems[] = ['label' => 'Table planning', 'route' => 'admin.table-plannings.index', 'active' => 'admin.table-plannings.*'];
        $navItems[] = ['label' => 'Navbar', 'route' => 'admin.navbar-items.index', 'active' => 'admin.navbar-items.*'];
        $navItems[] = ['label' => 'Sales summaries', 'route' => 'admin.sales-summaries.index', 'active' => 'admin.sales-summaries.*'];
    }

    if ($isAdmin || $isWaiter) {
        $navItems[] = ['label' => 'Dining sessions', 'route' => 'admin.dining-sessions.index', 'active' => 'admin.dining-sessions.*'];
        $navItems[] = ['label' => 'Waiter calls', 'route' => 'admin.service-requests.index', 'active' => 'admin.service-requests.*'];
    }

    if ($isAdmin || $isCashier) {
        $navItems[] = ['label' => 'Discounts', 'route' => 'admin.discounts.index', 'active' => 'admin.discounts.*'];
        $navItems[] = ['label' => 'Orders', 'route' => 'admin.orders.index', 'active' => 'admin.orders.*'];
    }
@endphp

@foreach ($navItems as $item)
    <a
        href="{{ route($item['route']) }}"
        class="block rounded-lg px-3 py-2 text-sm transition-colors {{ request()->routeIs($item['active']) ? 'bg-white/10 font-medium text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}"
    >
        {{ $item['label'] }}
    </a>
@endforeach
