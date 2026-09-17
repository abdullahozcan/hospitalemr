<?php

namespace App\Ths;

use App\Enums\PrescriptionStatus;
use App\Models\Encounter;
use App\Models\LabOrder;
use App\Models\Patient;
use App\Models\Prescription;
use Illuminate\Support\Str;

/**
 * Deterministic mock THS service.
 *
 * Mirrors the real web service behavior for development: submissions
 * succeed when the required data is present and fail with realistic
 * codes when it is not.
 */
class MockThsService implements ThsServiceInterface
{
    /**
     * Send the patient record to e-Nabız.
     */
    public function sendPatient(Patient $patient): ThsResponse
    {
        if (empty($patient->national_id)) {
            return ThsResponse::failure(
                'ENB-PAT-400',
                'Hasta T.C. kimlik numarası eksik, e-Nabız kaydı yapılamaz.',
            );
        }

        return ThsResponse::success(
            'ENB-PAT-200',
            'Hasta e-Nabız sistemine kaydedildi.',
            ['enabiz_patient_id' => 'ENB-'.Str::upper(Str::random(10))],
        );
    }

    /**
     * Send a completed encounter to e-Nabız.
     */
    public function sendEncounter(Encounter $encounter): ThsResponse
    {
        if ($encounter->ended_at === null) {
            return ThsResponse::failure(
                'ENB-ENC-400',
                'Muayene henüz tamamlanmamış, e-Nabız kaydı için bitiş zorunludur.',
            );
        }

        if (! $encounter->provider_id) {
            return ThsResponse::failure(
                'ENB-ENC-401',
                'Muayeneyi yapan hekim kaydı eksik.',
            );
        }

        return ThsResponse::success(
            'ENB-ENC-200',
            'Muayene kaydı e-Nabız sistemine iletildi.',
            ['submission_id' => 'SUB-'.Str::upper(Str::random(12))],
        );
    }

    /**
     * Send laboratory results to e-Nabız.
     */
    public function sendLabResults(LabOrder $order): ThsResponse
    {
        if ($order->status->value !== 'reviewed') {
            return ThsResponse::failure(
                'ENB-LAB-400',
                'Sonuçlar doktor tarafından incelenmeden e-Nabız gönderimi yapılamaz.',
            );
        }

        if ($order->results()->whereNull('resulted_at')->exists()) {
            return ThsResponse::failure(
                'ENB-LAB-401',
                'Sonuçlanmamış testler mevcut.',
            );
        }

        return ThsResponse::success(
            'ENB-LAB-200',
            'Laboratuvar sonuçları e-Nabız sistemine iletildi.',
            ['result_count' => $order->results()->count()],
        );
    }

    /**
     * Submit a prescription to SGK e-Reçete.
     */
    public function sendPrescription(Prescription $prescription): ThsResponse
    {
        if ($prescription->items()->doesntExist()) {
            return ThsResponse::failure(
                'SGK-RX-400',
                'Reçetede ilaç kalemi bulunmuyor.',
            );
        }

        if (empty($prescription->patient->national_id)) {
            return ThsResponse::failure(
                'SGK-RX-401',
                'Hastanın T.C. kimlik numarası SGK provizyonu için zorunludur.',
            );
        }

        $prescription->update(['status' => PrescriptionStatus::Dispensed->value]);

        return ThsResponse::success(
            'SGK-RX-200',
            'Reçete SGK e-Reçete sistemine gönderildi.',
            [
                'receipt_no' => Str::upper(Str::random(8)),
                'erecete_id' => 'ERX-'.Str::upper(Str::random(10)),
            ],
        );
    }

    /**
     * Query the SGK prescription workflow status.
     */
    public function prescriptionStatus(Prescription $prescription): ThsResponse
    {
        return ThsResponse::success(
            'SGK-ST-200',
            'İşemri durumu sorgulandı.',
            [
                'erecete_id' => 'ERX-'.Str::upper(Str::random(10)),
                'status' => 'ONAYLANDI',
            ],
        );
    }

    /**
     * Request SGK provision (coverage approval).
     */
    public function provisionRequest(Patient $patient, float $amount): ThsResponse
    {
        if (empty($patient->national_id)) {
            return ThsResponse::failure(
                'SGK-PRV-400',
                'Hastanın T.C. kimlik numarası provizyon için zorunludur.',
            );
        }

        return ThsResponse::success(
            'SGK-PRV-200',
            'SGK provizyon talebi onaylandı.',
            [
                'provision_no' => Str::upper(Str::random(10)),
                'approved_amount' => $amount,
            ],
        );
    }
}
