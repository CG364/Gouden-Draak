<?php

namespace App\Http\Controllers;

use App\Actions\Menu\AddSpecialOffersCategory;
use App\Actions\Menu\BuildPublicMenu;
use Barryvdh\DomPDF\PDF;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;

class MainSiteController extends Controller
{
    public function index()
    {
        return view('main.index');
    }

    public function contact()
    {
        return view('main.contact');
    }

    public function menu(BuildPublicMenu $buildPublicMenu, AddSpecialOffersCategory $addSpecialOffersCategory): View
    {
        return view('main.menu', [
            'dishKinds' => $addSpecialOffersCategory->handle($buildPublicMenu->handle()),
        ]);
    }

    /**
     * Generate a printable PDF of the full menu, for download.
     */
    public function menuPdf(BuildPublicMenu $buildPublicMenu, AddSpecialOffersCategory $addSpecialOffersCategory, PDF $pdf): Response
    {
        $dishKinds = $addSpecialOffersCategory->handle($buildPublicMenu->handle());

        return $pdf->loadView('main.menu-pdf', ['dishKinds' => $dishKinds])
            ->setPaper('a4')
            ->download('menu-de-gouden-draak.pdf');
    }
}
