<?php

use App\Http\Controllers\Admin\DailySalesSummaryController as AdminDailySalesSummaryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DiningSessionController as AdminDiningSessionController;
use App\Http\Controllers\Admin\DiscountController as AdminDiscountController;
use App\Http\Controllers\Admin\DishController as AdminDishController;
use App\Http\Controllers\Admin\DishKindController as AdminDishKindController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\ServiceRequestController as AdminServiceRequestController;
use App\Http\Controllers\Admin\SiteNavbarItemController as AdminSiteNavbarItemController;
use App\Http\Controllers\Admin\StaffController as AdminStaffController;
use App\Http\Controllers\Admin\TablePlanningController as AdminTablePlanningController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\MainSiteController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Tablet\MenuController as TabletMenuController;
use App\Http\Controllers\Tablet\OrderController as TabletOrderController;
use App\Http\Controllers\Tablet\ServiceRequestController as TabletServiceRequestController;
use App\Http\Controllers\TakeoutOrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MainSiteController::class, 'index'])->name('home');
Route::get('/contact', [MainSiteController::class, 'contact'])->name('contact');
Route::get('/menu', [MainSiteController::class, 'menu'])->name('menu');
Route::get('/menu/pdf', [MainSiteController::class, 'menuPdf'])->name('menu.pdf');
Route::get('/pages/{page:slug}', [PageController::class, 'show'])->name('pages.show');
Route::get('/lang/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');

Route::prefix('order')->name('order.')->group(function () {
    Route::get('/', [TakeoutOrderController::class, 'create'])->name('create');
    Route::post('/', [TakeoutOrderController::class, 'store'])->name('store');
    Route::get('/{order:token}', [TakeoutOrderController::class, 'show'])->name('show');
});

Route::prefix('admin')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('login', [AuthenticatedSessionController::class, 'store']);
    });

    Route::middleware(['auth', 'role:admin|cashier|waiter'])->name('admin.')->group(function () {
        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    });

    Route::middleware(['auth', 'role:admin|cashier'])->name('admin.')->group(function () {
        Route::resource('discounts', AdminDiscountController::class)->only(['index', 'create', 'store', 'destroy']);
        Route::resource('orders', AdminOrderController::class)->only(['index', 'create', 'store', 'show', 'destroy']);

        Route::middleware('role:admin')->group(function () {
            Route::resource('pages', AdminPageController::class)->except('show');
            Route::resource('dish-kinds', AdminDishKindController::class)->except('show');
            Route::resource('dishes', AdminDishController::class)->except('show');
            Route::resource('staff', AdminStaffController::class)->except('show');
            Route::resource('table-plannings', AdminTablePlanningController::class)->only(['index', 'create', 'store', 'destroy']);

            Route::resource('navbar-items', AdminSiteNavbarItemController::class)->except('show');
            Route::patch('navbar-items/{navbarItem}/move-up', [AdminSiteNavbarItemController::class, 'moveUp'])->name('navbar-items.move-up');
            Route::patch('navbar-items/{navbarItem}/move-down', [AdminSiteNavbarItemController::class, 'moveDown'])->name('navbar-items.move-down');

            Route::get('sales-summaries', [AdminDailySalesSummaryController::class, 'index'])->name('sales-summaries.index');
            Route::get('sales-summaries/{dailySalesSummary}/download', [AdminDailySalesSummaryController::class, 'download'])->name('sales-summaries.download');
        });
    });

    Route::middleware(['auth', 'role:admin|waiter'])->name('admin.')->group(function () {
        Route::resource('dining-sessions', AdminDiningSessionController::class)->only(['index', 'create', 'store', 'show']);
        Route::get('dining-sessions/{diningSession}/receipt', [AdminDiningSessionController::class, 'receiptPdf'])->name('dining-sessions.receipt');
        Route::patch('dining-sessions/{diningSession}/close', [AdminDiningSessionController::class, 'close'])->name('dining-sessions.close');

        Route::get('service-requests', [AdminServiceRequestController::class, 'index'])->name('service-requests.index');
        Route::patch('service-requests/{serviceRequest}/handle', [AdminServiceRequestController::class, 'handle'])->name('service-requests.handle');
    });
});

Route::prefix('tablet')->name('tablet.')->middleware('dining-session.active')->group(function () {
    Route::get('{diningSession}', [TabletMenuController::class, 'show'])->name('menu');
    Route::get('{diningSession}/status', [TabletMenuController::class, 'status'])->name('status');
    Route::post('{diningSession}/orders', [TabletOrderController::class, 'store'])->name('orders.store');
    Route::post('{diningSession}/service-requests', [TabletServiceRequestController::class, 'store'])->name('service-requests.store');
    Route::get('{diningSession}/service-requests/status', [TabletServiceRequestController::class, 'status'])->name('service-requests.status');
});
