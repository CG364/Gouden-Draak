<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LocaleController extends Controller
{
    public function switch(string $locale): RedirectResponse
    {
        if (! array_key_exists($locale, config('translatable.locales'))) {
            throw new NotFoundHttpException;
        }

        return redirect()->back()
            ->withCookie(cookie('locale', $locale, 60 * 24 * 365));
    }
}
