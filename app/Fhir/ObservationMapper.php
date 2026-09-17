<?php

namespace App\Fhir;

use App\Models\Vital;
use Illuminate\Database\Eloquent\Model;

class ObservationMapper extends FhirMapper
{
    /**
     * Vital sign measurement definitions in LOINC codes.
     *
     * @var array<string, array{code: string, display: string}>
     */
    protected const VITAL_CODES = [
        'height_cm' => ['code' => '8302-2', 'display' => 'Body height'],
        'weight_grams' => ['code' => '29463-7', 'display' => 'Body weight'],
        'systolic_mmhg' => ['code' => '8480-6', 'display' => 'Systolic blood pressure'],
        'diastolic_mmhg' => ['code' => '8462-4', 'display' => 'Diastolic blood pressure'],
        'pulse_bpm' => ['code' => '8867-4', 'display' => 'Heart rate'],
        'temperature_c' => ['code' => '8310-5', 'display' => 'Body temperature'],
        'spo2_percent' => ['code' => '2708-6', 'display' => 'Oxygen saturation'],
        'respiratory_rate' => ['code' => '9279-1', 'display' => 'Respiratory rate'],
    ];

    /**
     * Map a set of vitals from one encounter to FHIR Observation resources.
     *
     * @return list<array<string, mixed>>
     */
    public function toFhir(Model $model): array
    {
        /** @var Vital $model */
        $observations = [];

        foreach (self::VITAL_CODES as $attribute => $loinc) {
            $value = $this->vitalValue($model, $attribute);

            if ($value === null) {
                continue;
            }

            $observations[] = $this->observation($model, $attribute, $loinc, $value);
        }

        return $observations;
    }

    /**
     * Build one Observation resource.
     *
     * @param  array{code: string, display: string}  $loinc
     * @return array<string, mixed>
     */
    protected function observation(Vital $vital, string $attribute, array $loinc, float|int|string $value): array
    {
        $resource = $this->baseResource('Observation', $vital->getKey().'-'.$attribute, [
            'status' => 'final',
            'category' => [[
                'coding' => [[
                    'system' => 'http://terminology.hl7.org/CodeSystem/observation-category',
                    'code' => 'vital-signs',
                ]],
            ]],
            'code' => [
                'coding' => [[
                    'system' => 'http://loinc.org',
                    'code' => $loinc['code'],
                    'display' => $loinc['display'],
                ]],
                'text' => $loinc['display'],
            ],
            'subject' => [
                'reference' => 'Patient/'.$vital->encounter->patient_id,
            ],
            'effectiveDateTime' => $this->fhirDateTime($vital->measured_at ?? $vital->created_at),
        ]);

        if (is_numeric($value)) {
            $resource['valueQuantity'] = [
                'value' => (float) $value,
                'unit' => $this->unitFor($attribute),
                'system' => 'http://unitsofmeasure.org',
                'code' => $this->unitCodeFor($attribute),
            ];
        } else {
            $resource['valueString'] = (string) $value;
        }

        return $resource;
    }

    /**
     * Extract a measurement value from the vital.
     */
    protected function vitalValue(Vital $vital, string $attribute): float|int|string|null
    {
        $value = $vital->getAttribute($attribute);

        if ($value === null) {
            return null;
        }

        return $value;
    }

    /**
     * Map grams to kilograms for the weight measurement.
     */
    protected function unitFor(string $attribute): string
    {
        return match ($attribute) {
            'height_cm' => 'cm',
            'weight_grams' => 'kg',
            'systolic_mmhg', 'diastolic_mmhg' => 'mm[Hg]',
            'pulse_bpm' => '/min',
            'temperature_c' => 'Cel',
            'spo2_percent' => '%',
            'respiratory_rate' => '/min',
            default => '',
        };
    }

    /**
     * The UCUM code for the measurement unit.
     */
    protected function unitCodeFor(string $attribute): string
    {
        return match ($attribute) {
            'height_cm' => 'cm',
            'weight_grams' => 'kg',
            'systolic_mmhg', 'diastolic_mmhg' => 'mm[Hg]',
            'pulse_bpm', 'respiratory_rate' => '{beats}/min',
            'temperature_c' => 'Cel',
            'spo2_percent' => '%',
            default => '',
        };
    }

    /**
     * The FHIR resource type the mapper produces.
     */
    protected function resourceType(): string
    {
        return 'Observation';
    }

    /**
     * The FHIR profile this resource claims conformance to.
     */
    protected function profile(): string
    {
        return 'http://hl7.org/fhir/StructureDefinition/Observation';
    }
}
