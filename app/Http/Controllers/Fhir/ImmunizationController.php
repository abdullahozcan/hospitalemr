<?php

namespace App\Http\Controllers\Fhir;

use App\Fhir\FhirBundle;
use App\Fhir\ImmunizationMapper;
use App\Models\ApiClient;
use App\Models\Immunization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ImmunizationController extends Controller
{
    /**
     * The FHIR immunization mapper.
     */
    public function __construct(protected ImmunizationMapper $mapper) {}

    /**
     * Search immunizations.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Immunization::query()
            ->where('team_id', $this->teamId($request))
            ->when($request->query('patient'), fn ($q, $patientId) => $q->where('patient_id', (int) $patientId))
            ->limit(100);

        $resources = $query->get()
            ->map(fn (Immunization $immunization) => $this->mapper->toFhir($immunization))
            ->all();

        return response()->json(FhirBundle::searchset($resources, $request->fullUrl()));
    }

    /**
     * The team the request is scoped to.
     */
    protected function teamId(Request $request): int
    {
        /** @var ApiClient|null $client */
        $client = $request->attributes->get('api_client');

        return $client->team_id;
    }
}
