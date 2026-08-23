<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailySalesSummary;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DailySalesSummaryController extends Controller
{
    /**
     * Display a listing of the generated daily sales summaries.
     */
    public function index(): View
    {
        return view('admin.sales-summaries.index', [
            'salesSummaries' => DailySalesSummary::query()
                ->orderByDesc('date')
                ->paginate(31)
                ->onEachSide(1),
        ]);
    }

    /**
     * Download the Excel file for the specified daily sales summary.
     */
    public function download(DailySalesSummary $dailySalesSummary): StreamedResponse
    {
        return Storage::disk('local')->download(
            $dailySalesSummary->file_path,
            "omzet-{$dailySalesSummary->date->toDateString()}.xlsx",
        );
    }
}
