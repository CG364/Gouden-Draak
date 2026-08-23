<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Display the given page on the public site.
     */
    public function show(Page $page): View
    {
        return view('main.page', [
            'page' => $page,
        ]);
    }
}
