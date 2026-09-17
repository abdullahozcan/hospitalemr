<?php

namespace App\Fhir;

use App\Enums\PrescriptionStatus;
use App\Models\Prescription;
use Illuminate\Database\Eloquent\Model;

class MedicationRequestMapper extends FhirMapper
{
    /**
     * Map a prescription to one FHIR MedicationRequest resource per item.
     *
     * @return list<array<string, mixed>>
     */
    public function toFhir(Model $model): array
    {
        /** @var Prescription $model */
        $resources = [];

        foreach ($model->items as $item) {
            $dosage = [];

            if ($item->dosage) {
                $dosage['text'] = $item->dosage;
            }

            if ($item->duration_days) {
                $dosage['timing'] = [
                    'repeat' => [
                        'boundsDuration' => [
                            'value' => $item->duration_days,
                            'unit' => 'days',
                            'code' => 'd',
                        ],
                    ],
                ];
            }

            if ($item->instructions) {
                $dosage['patientInstruction'] = $item->instructions;
            }

            $resource = $this->baseResource('MedicationRequest', $model->getKey().'-'.$item->getKey(), [
                'status' => $this->statusCode($model),
                'intent' => 'order',
                'medicationCodeableConcept' => [
                    'text' => $item->drug->display_name,
                ],
                'subject' => [
                    'reference' => 'Patient/'.$model->patient_id,
                ],
                'authoredOn' => $this->fhirDateTime($model->issued_at),
                'dosageInstruction' => [$dosage],
            ]);

            if ($model->prescriber_id) {
                $resource['requester'] = [
                    'reference' => 'Practitioner/'.$model->prescriber_id,
                ];
            }

            $resources[] = $resource;
        }

        return $resources;
    }

    /**
     * Map the FHIR medication request status.
     */
    protected function statusCode(Prescription $prescription): string
    {
        return match ($prescription->status) {
            PrescriptionStatus::Draft => 'draft',
            PrescriptionStatus::Active => 'active',
            PrescriptionStatus::Dispensed => 'completed',
            PrescriptionStatus::Cancelled => 'cancelled',
        };
    }

    /**
     * The FHIR resource type the mapper produces.
     */
    protected function resourceType(): string
    {
        return 'MedicationRequest';
    }

    /**
     * The FHIR profile this resource claims conformance to.
     */
    protected function profile(): string
    {
        return 'http://hl7.org/fhir/StructureDefinition/MedicationRequest';
    }
}
