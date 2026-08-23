<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStaffRequest;
use App\Http\Requests\Admin\UpdateStaffRequest;
use App\Models\Staff;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StaffController extends Controller
{
    /**
     * Display a listing of the staff members.
     */
    public function index(): View
    {
        return view('admin.staff.index', [
            'staffMembers' => Staff::query()->orderBy('last_name')->orderBy('first_name')->paginate(15)->onEachSide(1),
        ]);
    }

    /**
     * Show the form for creating a new staff member.
     */
    public function create(): View
    {
        return view('admin.staff.create', [
            'staff' => new Staff,
        ]);
    }

    /**
     * Store a newly created staff member in storage.
     */
    public function store(StoreStaffRequest $request): RedirectResponse
    {
        Staff::query()->create($request->validated());

        return redirect()->route('admin.staff.index')->with('status', 'Employee created.');
    }

    /**
     * Show the form for editing the specified staff member.
     */
    public function edit(Staff $staff): View
    {
        return view('admin.staff.edit', [
            'staff' => $staff,
            'tablePlannings' => $staff->tablePlannings()->with('table')->orderBy('start')->get(),
        ]);
    }

    /**
     * Update the specified staff member in storage.
     */
    public function update(UpdateStaffRequest $request, Staff $staff): RedirectResponse
    {
        $staff->update($request->validated());

        return redirect()->route('admin.staff.index')->with('status', 'Employee updated.');
    }

    /**
     * Remove the specified staff member from storage.
     */
    public function destroy(Staff $staff): RedirectResponse
    {
        $staff->delete();

        return redirect()->route('admin.staff.index')->with('status', 'Employee deleted.');
    }
}
