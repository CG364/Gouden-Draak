<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Dishes\ListDishesPaginated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDishRequest;
use App\Http\Requests\Admin\UpdateDishRequest;
use App\Models\Dish;
use App\Models\DishKind;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DishController extends Controller
{
    /**
     * Display a listing of the dishes.
     */
    public function index(ListDishesPaginated $listDishes): View
    {
        return view('admin.dishes.index', [
            'dishes' => $listDishes->handle(),
        ]);
    }

    /**
     * Show the form for creating a new dish.
     */
    public function create(): View
    {
        return view('admin.dishes.create', [
            'dish' => new Dish,
            'dishKinds' => DishKind::query()->orderBy('id')->get(),
        ]);
    }

    /**
     * Store a newly created dish in storage.
     */
    public function store(StoreDishRequest $request): RedirectResponse
    {
        Dish::query()->create($request->validated());

        return redirect()->route('admin.dishes.index')->with('status', 'Dish created.');
    }

    /**
     * Show the form for editing the specified dish.
     */
    public function edit(Dish $dish): View
    {
        return view('admin.dishes.edit', [
            'dish' => $dish,
            'dishKinds' => DishKind::query()->orderBy('id')->get(),
        ]);
    }

    /**
     * Update the specified dish in storage.
     */
    public function update(UpdateDishRequest $request, Dish $dish): RedirectResponse
    {
        $dish->update($request->validated());

        return redirect()->route('admin.dishes.index')->with('status', 'Dish updated.');
    }

    /**
     * Remove the specified dish from storage.
     */
    public function destroy(Dish $dish): RedirectResponse
    {
        $dish->delete();

        return redirect()->route('admin.dishes.index')->with('status', 'Dish deleted.');
    }
}
