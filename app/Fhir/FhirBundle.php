<?php

namespace App\Fhir;

/**
 * Builds FHIR Bundle searchset responses.
 */
class FhirBundle
{
    /**
     * Build a searchset bundle from FHIR resources.
     *
     * @param  array<int, array<string, mixed>>  $resources
     * @return array<string, mixed>
     */
    public static function searchset(array $resources, ?string $selfUrl = null): array
    {
        $entries = [];

        foreach ($resources as $resource) {
            $entries[] = [
                'fullUrl' => url('/api/fhir/'.$resource['resourceType'].'/'.$resource['id']),
                'resource' => $resource,
            ];
        }

        $bundle = [
            'resourceType' => 'Bundle',
            'type' => 'searchset',
            'total' => count($entries),
            'entry' => $entries,
        ];

        if ($selfUrl !== null) {
            $bundle['link'] = [
                [
                    'relation' => 'self',
                    'url' => $selfUrl,
                ],
            ];
        }

        return $bundle;
    }

    /**
     * Build a FHIR OperationOutcome.
     *
     * @return array<string, mixed>
     */
    public static function outcome(string $severity, string $code, string $diagnostics): array
    {
        return [
            'resourceType' => 'OperationOutcome',
            'issue' => [
                [
                    'severity' => $severity,
                    'code' => $code,
                    'diagnostics' => $diagnostics,
                ],
            ],
        ];
    }
}
