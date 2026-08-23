<?php

namespace App\Http\Middleware;

use App\Models\DiningSession;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureDiningSessionIsActive
{
    /**
     * Handle an incoming request.
     *
     * Stops a tablet from placing or viewing orders once a waiter has closed
     * out its dining session (e.g. after the table has paid and left).
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var DiningSession $diningSession */
        $diningSession = $request->route('diningSession');

        abort_if($diningSession->ended_at !== null, 410, 'This dining session has ended.');

        return $next($request);
    }
}
