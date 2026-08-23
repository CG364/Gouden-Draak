<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $available = array_keys(config('translatable.locales'));
        $locale = $request->cookie('locale');

        if (! is_string($locale) || ! in_array($locale, $available, true)) {
            $locale = $request->getPreferredLanguage($available) ?? config('app.locale');
        }

        App::setLocale($locale);

        return $next($request);
    }
}
