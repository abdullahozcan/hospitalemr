<?php

namespace App\Observers;

use App\Enums\PrescriptionStatus;
use App\Jobs\SyncPrescriptionToThs;
use App\Models\Prescription;

class PrescriptionObserver
{
    /**
     * Queue an SGK submission when a prescription becomes active.
     */
    public function updated(Prescription $prescription): void
    {
        if (! $prescription->wasChanged('status')) {
            return;
        }

        if ($prescription->status !== PrescriptionStatus::Active
            || $prescription->getOriginal('status') !== PrescriptionStatus::Draft) {
            return;
        }

        SyncPrescriptionToThs::dispatch($prescription);
    }
}
