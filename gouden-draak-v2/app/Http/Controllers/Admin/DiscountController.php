<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Discounts\CreateDiscount;
use App\Actions\Discounts\ListDishKindsForDiscountSelection;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDiscountRequest;
use App\Models\Discount;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DiscountController extends Controller
{
    /**
     * Display a listing of the discounts.
     */
    public function index(): View
    {
        return view('admin.discounts.index', [
            'discounts' => Discount::query()->with('dishes')->orderByDesc('starts_at')->paginate(15)->onEachSide(1),
        ]);
    }

    /**
     * Show the form for creating a new discount.
     */
    public function create(ListDishKindsForDiscountSelection $listDishKinds): View
    {
        return view('admin.discounts.create', [
            'dishKinds' => $listDishKinds->handle(),
        ]);
    }

    /**
     * Store a newly created discount in storage.
     */
    public function store(StoreDiscountRequest $request, CreateDiscount $createDiscount): RedirectResponse
    {
        $createDiscount->handle($request->validated());

        return redirect()->route('admin.discounts.index')->with('status', 'Discount created.');
    }

    /**
     * Remove the specified discount from storage.
     */
    public function destroy(Discount $discount): RedirectResponse
    {
        $discount->delete();

        return redirect()->route('admin.discounts.index')->with('status', 'Discount deleted.');
    }
}
