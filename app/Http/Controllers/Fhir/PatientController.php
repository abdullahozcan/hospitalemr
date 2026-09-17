<?php

namespace App\Http\Controllers\Fhir;

use App\Fhir\FhirBundle;
use App\Fhir\PatientMapper;
use App\Models\ApiClient;
use App\Models\Patient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PatientController extends Controller
{
    /**
     * The FHIR patient mapper.
     */
    public function __construct(protected PatientMapper $mapper) {}

    /**
     * Read a single patient.
     */
    public function show(Request $request, Patient $patient): JsonResponse
    {
        $this->authorizeTeam($request, $patient->team_id);

        return response()->json($this->mapper->toFhir($patient));
    }

    /**
     * Search patients.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Patient::query()
            ->where('team_id', $this->teamId($request))
            ->when($request->query('_id'), fn ($q, $id) => $q->whereKey((int) $id))
            ->when(is_string($mrn = $request->query('identifier')), fn ($q) => $q->where('mrn', $mrn))
            ->when(is_string($family = $request->query('family')), fn ($q) => $q->where('last_name', 'like', '%'.$family.'%'))
            ->when(is_string($given = $request->query('given')), fn ($q) => $q->where('first_name', 'like', '%'.$given.'%'))
            ->when(is_string($gender = $request->query('gender')), fn ($q) => $q->where('gender', $gender))
            ->limit(100);

        $resources = $query->get()
            ->map(fn (Patient $patient) => $this->mapper->toFhir($patient))
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

    /**
     * Reject reads outside the client's team.
     */
    protected function authorizeTeam(Request $request, int $teamId): void
    {
        abort_if($this->teamId($request) !== $teamId, 404, 'Resource not found.');
    }
}
