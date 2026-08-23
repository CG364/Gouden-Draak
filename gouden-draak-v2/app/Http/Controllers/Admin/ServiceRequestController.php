<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ServiceRequestController extends Controller
{
    /**
     * Display a listing of the unhandled waiter-call requests.
     */
    public function index(): View
    {
        return view('admin.service-requests.index', [
            'serviceRequests' => ServiceRequest::query()
                ->unhandled()
                ->with('table')
                ->oldest()
                ->get(),
        ]);
    }

    /**
     * Mark the specified service request as handled.
     */
    public function handle(ServiceRequest $serviceRequest): RedirectResponse
    {
        $serviceRequest->update(['handled' => true]);

        return redirect()->route('admin.service-requests.index')->with('status', 'Waiter call marked as handled.');
    }
}
