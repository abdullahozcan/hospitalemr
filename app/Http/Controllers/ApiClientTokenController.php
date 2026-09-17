<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ApiClientTokenController extends Controller
{
    /**
     * Show a freshly generated API token once.
     */
    public function show(Request $request): View|RedirectResponse
    {
        abort_unless($request->user()->hasRole('admin'), 403);

        $token = session()->pull('api-client-token');

        if ($token === null) {
            return redirect('/admin/api-clients');
        }

        return view('filament.api-client-token', [
            'token' => $token,
        ]);
    }
}
