<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PrescriptionPrintController extends Controller
{
    /**
     * Show a printable prescription sheet.
     */
    public function __invoke(Request $request, Prescription $prescription): View
    {
        abort_unless($request->user()->can('view', $prescription), 403);

        return view('print.prescription', [
            'prescription' => $prescription->load(['patient', 'prescriber', 'items.drug']),
        ]);
    }
}
