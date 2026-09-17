<?php

namespace App\Http\Controllers\Fhir;

use App\Fhir\FhirBundle;
use App\Fhir\ObservationMapper;
use App\Models\ApiClient;
use App\Models\Vital;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ObservationController extends Controller
{
    /**
     * The FHIR observation mapper.
     */
    public function __construct(protected ObservationMapper $mapper) {}

    /**
     * Search vital sign observations.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Vital::query()
            ->where('team_id', $this->teamId($request))
            ->when($request->query('patient'), function ($q, $patientId) {
                $q->whereHas('encounter', fn ($encounterQuery) => $encounterQuery->where('patient_id', (int) $patientId));
            })
            ->limit(100);

        $resources = $query->get()
            ->flatMap(fn (Vital $vital) => $this->mapper->toFhir($vital))
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
