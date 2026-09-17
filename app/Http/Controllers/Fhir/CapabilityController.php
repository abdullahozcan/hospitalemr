<?php

namespace App\Http\Controllers\Fhir;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class CapabilityController extends Controller
{
    /**
     * Serve the FHIR CapabilityStatement for this server.
     */
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'resourceType' => 'CapabilityStatement',
            'status' => 'active',
            'date' => now()->toIso8601String(),
            'publisher' => config('app.name'),
            'kind' => 'instance',
            'software' => [
                'name' => config('app.name'),
                'version' => '1.0.0',
            ],
            'fhirVersion' => '4.0.1',
            'format' => ['application/fhir+json', 'application/json'],
            'rest' => [
                [
                    'mode' => 'server',
                    'security' => [
                        'description' => 'Bearer token (Sanctum) with fhir.read ability required.',
                    ],
                    'resource' => [
                        $this->resource('Patient', ['read', 'search-type']),
                        $this->resource('Encounter', ['read', 'search-type'], ['patient']),
                        $this->resource('Observation', ['search-type'], ['patient']),
                        $this->resource('Immunization', ['search-type'], ['patient']),
                        $this->resource('MedicationRequest', ['search-type'], ['patient']),
                    ],
                ],
            ],
        ]);
    }

    /**
     * Build one resource capability entry.
     *
     * @param  list<string>  $interactions
     * @param  list<string>  $searchParams
     * @return array<string, mixed>
     */
    protected function resource(string $type, array $interactions, array $searchParams = []): array
    {
        $entry = [
            'type' => $type,
            'interaction' => array_map(fn (string $interaction) => ['code' => $interaction], $interactions),
        ];

        if ($searchParams !== []) {
            $entry['searchParam'] = array_map(fn (string $param) => [
                'name' => $param,
                'type' => $param === 'patient' ? 'reference' : 'token',
            ], $searchParams);
        }

        return $entry;
    }
}
