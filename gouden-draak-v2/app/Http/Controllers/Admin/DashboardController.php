<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiningSession;
use App\Models\Discount;
use App\Models\Dish;
use App\Models\DishKind;
use App\Models\Order;
use App\Models\Page;
use App\Models\ServiceRequest;
use App\Models\Staff;
use App\Models\TablePlanning;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index(): View
    {
        $isAdmin = auth()->user()?->hasRole('admin') ?? false;
        $isCashier = auth()->user()?->hasRole('cashier') ?? false;
        $isWaiter = auth()->user()?->hasRole('waiter') ?? false;

        return view('admin.dashboard', [
            'isAdmin' => $isAdmin,
            'isCashier' => $isCashier,
            'isWaiter' => $isWaiter,
            'pageCount' => Page::query()->count(),
            'dishCount' => Dish::query()->count(),
            'dishKindCount' => DishKind::query()->count(),
            'staffCount' => Staff::query()->count(),
            'tablePlanningCount' => TablePlanning::query()->count(),
            'discountCount' => Discount::query()->count(),
            'orderCount' => Order::query()->count(),
            'activeDiningSessionCount' => DiningSession::query()->active()->count(),
            'unhandledServiceRequestCount' => ServiceRequest::query()->unhandled()->count(),
        ]);
    }
}
