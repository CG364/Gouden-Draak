<?php

namespace App\Http\Controllers\Admin;

use App\Actions\DiningSessions\BuildSessionReceiptLines;
use App\Actions\DiningSessions\ListAvailableTables;
use App\Actions\DiningSessions\OpenDiningSession;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDiningSessionRequest;
use App\Models\DiningSession;
use App\Models\Table;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;

class DiningSessionController extends Controller
{
    /**
     * Display a listing of the dining sessions.
     */
    public function index(): View
    {
        return view('admin.dining-sessions.index', [
            'maxOrders' => DiningSession::MAX_ORDERS,
            'activeDiningSessions' => DiningSession::query()
                ->active()
                ->with(['table', 'openedBy'])
                ->withCount('orders')
                ->orderBy('started_at')
                ->get(),
            'closedDiningSessions' => DiningSession::query()
                ->whereNotNull('ended_at')
                ->with(['table', 'openedBy'])
                ->withCount('orders')
                ->orderByDesc('ended_at')
                ->paginate(15)
                ->onEachSide(1),
        ]);
    }

    /**
     * Show the form for starting a new dining session.
     */
    public function create(ListAvailableTables $listAvailableTables): View
    {
        return view('admin.dining-sessions.create', [
            'tables' => $listAvailableTables->handle(),
            'maxGuests' => DiningSession::MAX_GUESTS,
        ]);
    }

    /**
     * Start a new dining session for a table.
     */
    public function store(StoreDiningSessionRequest $request, OpenDiningSession $openDiningSession): RedirectResponse
    {
        $diningSession = $openDiningSession->handle(
            Table::query()->findOrFail($request->validated('table_id')),
            $request->user(),
            $request->validated('guest_count'),
            array_map('intval', $request->validated('guest_ages')),
            $request->boolean('wants_extra_deluxe_menu'),
        );

        return redirect()->route('admin.dining-sessions.show', $diningSession);
    }

    /**
     * Display the tablet link for the specified dining session.
     */
    public function show(DiningSession $diningSession): View
    {
        return view('admin.dining-sessions.show', [
            'diningSession' => $diningSession->load('table'),
        ]);
    }

    /**
     * Generate a printable receipt PDF for the specified dining session.
     */
    public function receiptPdf(DiningSession $diningSession, BuildSessionReceiptLines $buildSessionReceiptLines, PDF $pdf): Response
    {
        $diningSession->load(['table', 'orders.items.dish']);

        $lines = $buildSessionReceiptLines->handle($diningSession);

        // 8.5cm x 10cm, converted to points (dompdf's setPaper unit; 1 inch = 72pt = 2.54cm).
        $width = 8.5 / 2.54 * 72;
        $height = 10 / 2.54 * 72;

        return $pdf->loadView('admin.dining-sessions.receipt-pdf', [
            'diningSession' => $diningSession,
            'lines' => $lines,
            'total' => $lines->sum('lineTotal'),
        ])
            ->setPaper([0, 0, $width, $height])
            ->download("receipt-table-{$diningSession->table->nr}.pdf");
    }

    /**
     * Close the specified dining session.
     */
    public function close(DiningSession $diningSession): RedirectResponse
    {
        $diningSession->update(['ended_at' => now()]);

        return redirect()->route('admin.dining-sessions.index')->with('status', 'Dining session closed.');
    }
}
