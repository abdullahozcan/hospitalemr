<?php

namespace App\Fhir;

use App\Models\Encounter;
use Illuminate\Database\Eloquent\Model;

class EncounterMapper extends FhirMapper
{
    /**
     * Map an encounter to a FHIR R4 Encounter resource.
     *
     * @return array<string, mixed>
     */
    public function toFhir(Model $model): array
    {
        /** @var Encounter $model */
        $resource = $this->baseResource('Encounter', $model->getKey(), [
            'identifier' => [
                ['system' => 'urn:oid:2.16.840.1.113883.3.99.1', 'value' => 'ENC-'.$model->getKey()],
            ],
            'status' => $this->statusCode($model),
            'class' => [
                'system' => 'http://terminology.hl7.org/CodeSystem/v3-ActCode',
                'code' => 'AMB',
                'display' => 'ambulatory',
            ],
            'subject' => [
                'reference' => 'Patient/'.$model->patient_id,
            ],
            'period' => [
                'start' => $this->fhirDateTime($model->started_at),
            ],
        ]);

        if ($model->ended_at) {
            $resource['period']['end'] = $this->fhirDateTime($model->ended_at);
        }

        if ($model->chief_complaint) {
            $resource['reasonCode'][] = [
                'text' => $model->chief_complaint,
            ];
        }

        if ($model->provider_id) {
            $resource['participant'][] = [
                'individual' => ['reference' => 'Practitioner/'.$model->provider_id],
            ];
        }

        return $resource;
    }

    /**
     * Map the FHIR encounter status.
     */
    protected function statusCode(Encounter $encounter): string
    {
        return $encounter->ended_at === null ? 'in-progress' : 'finished';
    }

    /**
     * The FHIR resource type the mapper produces.
     */
    protected function resourceType(): string
    {
        return 'Encounter';
    }

    /**
     * The FHIR profile this resource claims conformance to.
     */
    protected function profile(): string
    {
        return 'http://hl7.org/fhir/StructureDefinition/Encounter';
    }
}
