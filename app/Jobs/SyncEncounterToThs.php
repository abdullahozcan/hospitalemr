<?php

namespace App\Jobs;

use App\Models\Encounter;
use App\Models\LabOrder;
use App\Models\Prescription;
use App\Ths\ThsResponse;
use App\Ths\ThsServiceInterface;
use Illuminate\Foundation\Bus\Dispatchable;

/**
 * Sends a completed encounter to e-Nabız.
 */
class SyncEncounterToThs extends ThsSyncJob
{
    use Dispatchable;

    /**
     * Create the job.
     */
    public function __construct(public readonly Encounter $subject)
    {
        parent::__construct();
    }

    /**
     * Run the THS operation for the subject.
     */
    protected function perform(ThsServiceInterface $ths): ThsResponse
    {
        return $ths->sendEncounter($this->subject);
    }

    /**
     * The subject model being synchronized.
     */
    public function getSubject(): Encounter|LabOrder|Prescription
    {
        return $this->subject;
    }

    /**
     * The THS operation name.
     */
    public function getOperation(): string
    {
        return 'sendEncounter';
    }
}
