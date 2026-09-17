<?php

namespace App\Fhir;

use App\Models\Immunization;
use Illuminate\Database\Eloquent\Model;

class ImmunizationMapper extends FhirMapper
{
    /**
     * Map an immunization to a FHIR R4 Immunization resource.
     *
     * @return array<string, mixed>
     */
    public function toFhir(Model $model): array
    {
        /** @var Immunization $model */
        $resource = $this->baseResource('Immunization', $model->getKey(), [
            'status' => 'completed',
            'vaccineCode' => [
                'text' => $model->vaccine_name,
            ],
            'patient' => [
                'reference' => 'Patient/'.$model->patient_id,
            ],
            'occurrenceDateTime' => $this->fhirDateTime($model->administered_at),
        ]);

        if ($model->dose_number) {
            $resource['protocolApplied'][] = [
                'doseNumberPositiveInt' => $model->dose_number,
            ];
        }

        if ($model->administered_by) {
            $resource['performer'][] = [
                'actor' => ['reference' => 'Practitioner/'.$model->administered_by],
            ];
        }

        return $resource;
    }

    /**
     * The FHIR resource type the mapper produces.
     */
    protected function resourceType(): string
    {
        return 'Immunization';
    }

    /**
     * The FHIR profile this resource claims conformance to.
     */
    protected function profile(): string
    {
        return 'http://hl7.org/fhir/StructureDefinition/Immunization';
    }
}
