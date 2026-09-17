<?php

namespace App\Fhir;

use App\Enums\Gender;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Model;

class PatientMapper extends FhirMapper
{
    /**
     * Map a patient to a FHIR R4 Patient resource.
     *
     * @return array<string, mixed>
     */
    public function toFhir(Model $model): array
    {
        /** @var Patient $model */
        $resource = $this->baseResource('Patient', $model->getKey(), [
            'identifier' => [
                ['system' => 'urn:oid:2.16.840.1.113883.2.12', 'value' => $model->mrn],
            ],
            'active' => ! $model->is_deceased,
            'name' => [
                [
                    'family' => $model->last_name,
                    'given' => [$model->first_name],
                ],
            ],
            'gender' => $this->genderCode($model->gender),
            'birthDate' => $this->fhirDate($model->birth_date),
        ]);

        if ($model->phone) {
            $resource['telecom'][] = ['system' => 'phone', 'value' => $model->phone];
        }

        if ($model->email) {
            $resource['telecom'][] = ['system' => 'email', 'value' => $model->email];
        }

        if ($model->address) {
            $resource['address'][] = [
                'text' => $model->address,
                'city' => $model->city,
                'district' => $model->district,
            ];
        }

        if ($model->is_deceased && $model->deceased_at) {
            $resource['deceasedDateTime'] = $this->fhirDateTime($model->deceased_at);
        }

        return $resource;
    }

    /**
     * Map the FHIR gender code.
     */
    protected function genderCode(?Gender $gender): ?string
    {
        return match ($gender) {
            Gender::Male => 'male',
            Gender::Female => 'female',
            default => 'other',
        };
    }

    /**
     * The FHIR resource type the mapper produces.
     */
    protected function resourceType(): string
    {
        return 'Patient';
    }

    /**
     * The FHIR profile this resource claims conformance to.
     */
    protected function profile(): string
    {
        return 'http://hl7.org/fhir/StructureDefinition/Patient';
    }
}
