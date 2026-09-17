<?php

namespace App\Observers;

use App\Jobs\SyncEncounterToThs;
use App\Models\Encounter;

class EncounterObserver
{
    /**
     * Queue an e-Nabız submission when an encounter is completed.
     */
    public function updated(Encounter $encounter): void
    {
        if (! $this->isTransitionToCompleted($encounter)) {
            return;
        }

        SyncEncounterToThs::dispatch($encounter);
    }

    /**
     * Whether the encounter just transitioned to completed.
     */
    protected function isTransitionToCompleted(Encounter $encounter): bool
    {
        return $encounter->ended_at !== null
            && $encounter->wasChanged('ended_at')
            && $encounter->getOriginal('ended_at') === null;
    }
}
