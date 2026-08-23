<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTablePlanningRequest;
use App\Models\Staff;
use App\Models\Table;
use App\Models\TablePlanning;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TablePlanningController extends Controller
{
    /**
     * Display a listing of the table planning entries.
     */
    public function index(): View
    {
        return view('admin.table-plannings.index', [
            'tablePlannings' => TablePlanning::query()->with(['staff', 'table'])->orderByDesc('start')->paginate(15),
        ]);
    }

    /**
     * Show the form for creating a new table planning.
     */
    public function create(): View
    {
        return view('admin.table-plannings.create', [
            'staffMembers' => Staff::query()->orderBy('last_name')->orderBy('first_name')->get(),
            'tables' => Table::query()->orderBy('nr')->get(),
        ]);
    }

    /**
     * Store a newly created table planning in storage.
     */
    public function store(StoreTablePlanningRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        foreach ($validated['table_ids'] as $tableId) {
            TablePlanning::query()->create([
                'table_id' => $tableId,
                'staff_id' => $validated['staff_id'],
                'start' => $validated['start'],
                'end' => $validated['end'],
            ]);
        }

        return redirect()->route('admin.table-plannings.index')->with('status', 'Planning created.');
    }

    /**
     * Remove the specified table planning entry from storage.
     */
    public function destroy(TablePlanning $tablePlanning): RedirectResponse
    {
        $tablePlanning->delete();

        return redirect()->route('admin.table-plannings.index')->with('status', 'Planning entry deleted.');
    }
}
