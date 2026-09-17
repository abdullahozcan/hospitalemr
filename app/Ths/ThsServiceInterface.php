<?php

namespace App\Ths;

use App\Models\Encounter;
use App\Models\LabOrder;
use App\Models\Patient;
use App\Models\Prescription;

/**
 * Contract for Türkiye Sağlık Sistemi integrations (e-Nabız and SGK).
 */
interface ThsServiceInterface
{
    /**
     * Send the patient record to e-Nabız.
     */
    public function sendPatient(Patient $patient): ThsResponse;

    /**
     * Send a completed encounter to e-Nabız.
     */
    public function sendEncounter(Encounter $encounter): ThsResponse;

    /**
     * Send laboratory results to e-Nabız.
     */
    public function sendLabResults(LabOrder $order): ThsResponse;

    /**
     * Submit a prescription to SGK e-Reçete.
     */
    public function sendPrescription(Prescription $prescription): ThsResponse;

    /**
     * Query the SGK prescription workflow status.
     */
    public function prescriptionStatus(Prescription $prescription): ThsResponse;

    /**
     * Request SGK provision (coverage approval).
     */
    public function provisionRequest(Patient $patient, float $amount): ThsResponse;
}
