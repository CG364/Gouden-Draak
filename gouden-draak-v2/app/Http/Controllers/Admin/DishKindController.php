<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDishKindRequest;
use App\Http\Requests\Admin\UpdateDishKindRequest;
use App\Models\DishKind;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DishKindController extends Controller
{
    /**
     * Display a listing of the dish kinds.
     */
    public function index(): View
    {
        return view('admin.dish-kinds.index', [
            'dishKinds' => DishKind::query()->withCount('dishes')->orderBy('id')->paginate(15),
        ]);
    }

    /**
     * Show the form for creating a new dish kind.
     */
    public function create(): View
    {
        return view('admin.dish-kinds.create', [
            'dishKind' => new DishKind,
        ]);
    }

    /**
     * Store a newly created dish kind in storage.
     */
    public function store(StoreDishKindRequest $request): RedirectResponse
    {
        DishKind::query()->create($request->validated());

        return redirect()->route('admin.dish-kinds.index')->with('status', 'Dish kind created.');
    }

    /**
     * Show the form for editing the specified dish kind.
     */
    public function edit(DishKind $dishKind): View
    {
        return view('admin.dish-kinds.edit', [
            'dishKind' => $dishKind,
        ]);
    }

    /**
     * Update the specified dish kind in storage.
     */
    public function update(UpdateDishKindRequest $request, DishKind $dishKind): RedirectResponse
    {
        $dishKind->update($request->validated());

        return redirect()->route('admin.dish-kinds.index')->with('status', 'Dish kind updated.');
    }

    /**
     * Remove the specified dish kind from storage.
     */
    public function destroy(DishKind $dishKind): RedirectResponse
    {
        if ($dishKind->dishes()->exists()) {
            return redirect()->route('admin.dish-kinds.index')
                ->with('error', 'This dish kind still has dishes assigned to it. Reassign or delete them first.');
        }

        $dishKind->delete();

        return redirect()->route('admin.dish-kinds.index')->with('status', 'Dish kind deleted.');
    }
}
