<?php

namespace App\Ths;

use App\Models\Encounter;
use App\Models\LabOrder;
use App\Models\Patient;
use App\Models\Prescription;
use RuntimeException;

/**
 * Live SOAP THS service.
 *
 * Requires institution credentials, a signed mTLS certificate and THS
 * endpoint access. The transport layer is wired in config/ths.php; until
 * the institution credentials are provisioned this implementation only
 * validates the configuration and returns typed failures.
 */
class SoapThsService implements ThsServiceInterface
{
    /**
     * Create the service and validate the live configuration.
     */
    public function __construct()
    {
        $institution = config('ths.institution');

        if (empty($institution['code']) || empty($institution['certificate_path'])) {
            throw new RuntimeException(
                'Canlı THS entegrasyonu için THS_INSTITUTION_CODE ve THS_CERTIFICATE_PATH ayarlanmalıdır.'
            );
        }
    }

    /**
     * Send the patient record to e-Nabız.
     */
    public function sendPatient(Patient $patient): ThsResponse
    {
        return $this->notImplemented('HastaKaydet');
    }

    /**
     * Send a completed encounter to e-Nabız.
     */
    public function sendEncounter(Encounter $encounter): ThsResponse
    {
        return $this->notImplemented('MuayeneEkle');
    }

    /**
     * Send laboratory results to e-Nabız.
     */
    public function sendLabResults(LabOrder $order): ThsResponse
    {
        return $this->notImplemented('LabSonucGonder');
    }

    /**
     * Submit a prescription to SGK e-Reçete.
     */
    public function sendPrescription(Prescription $prescription): ThsResponse
    {
        return $this->notImplemented('ReceteGiris');
    }

    /**
     * Query the SGK prescription workflow status.
     */
    public function prescriptionStatus(Prescription $prescription): ThsResponse
    {
        return $this->notImplemented('IsemriOku');
    }

    /**
     * Request SGK provision (coverage approval).
     */
    public function provisionRequest(Patient $patient, float $amount): ThsResponse
    {
        return $this->notImplemented('ProvizyonGiris');
    }

    /**
     * Wrap the unimplemented transport in a typed response.
     */
    protected function notImplemented(string $operation): ThsResponse
    {
        return ThsResponse::failure(
            'THS-501',
            '[SoapThsService] '.$operation.' taşıması henüz uygulanmadı; mock sürücüsü kullanın.',
        );
    }
}
