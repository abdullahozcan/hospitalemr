<?php

namespace App\Http\Controllers\Fhir;

use App\Fhir\EncounterMapper;
use App\Fhir\FhirBundle;
use App\Models\ApiClient;
use App\Models\Encounter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;

class EncounterController extends Controller
{
    /**
     * The FHIR encounter mapper.
     */
    public function __construct(protected EncounterMapper $mapper) {}

    /**
     * Read a single encounter.
     */
    public function show(Request $request, Encounter $encounter): JsonResponse
    {
        abort_if($this->teamId($request) !== $encounter->team_id, 404, 'Resource not found.');

        return response()->json($this->mapper->toFhir($encounter));
    }

    /**
     * Search encounters.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Encounter::query()
            ->where('team_id', $this->teamId($request))
            ->when($request->query('patient'), fn ($q, $patientId) => $q->where('patient_id', (int) $patientId))
            ->when($request->query('date'), fn ($q, $date) => $q->whereBetween('started_at', [
                Carbon::parse(is_string($date) ? $date : 'now')->startOfDay(),
                Carbon::parse(is_string($date) ? $date : 'now')->endOfDay(),
            ]))
            ->limit(100);

        $resources = $query->get()
            ->map(fn (Encounter $encounter) => $this->mapper->toFhir($encounter))
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
