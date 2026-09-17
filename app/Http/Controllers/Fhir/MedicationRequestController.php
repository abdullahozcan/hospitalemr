<?php

namespace App\Http\Controllers\Fhir;

use App\Fhir\FhirBundle;
use App\Fhir\MedicationRequestMapper;
use App\Models\ApiClient;
use App\Models\Prescription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class MedicationRequestController extends Controller
{
    /**
     * The FHIR medication request mapper.
     */
    public function __construct(protected MedicationRequestMapper $mapper) {}

    /**
     * Search medication requests.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Prescription::query()
            ->where('team_id', $this->teamId($request))
            ->with(['items.drug'])
            ->when($request->query('patient'), fn ($q, $patientId) => $q->where('patient_id', (int) $patientId))
            ->limit(100);

        $resources = $query->get()
            ->flatMap(fn (Prescription $prescription) => $this->mapper->toFhir($prescription))
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
