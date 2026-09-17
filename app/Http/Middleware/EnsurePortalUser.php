<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

/**
 * Ensures the signed-in user is a patient portal user.
 */
class EnsurePortalUser
{
    /**
     * Handle the incoming request.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (! $request->user()?->isPatient()) {
            return Redirect::route('login');
        }

        return $next($request);
    }
}
