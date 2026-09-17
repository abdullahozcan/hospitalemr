<?php

namespace App\Fhir;

use App\Models\ApiAccessLog;
use App\Models\ApiClient;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Laravel\Sanctum\PersonalAccessToken;

/**
 * Authenticates FHIR requests with a Sanctum bearer token belonging to an active API client.
 */
class ApiClientMiddleware
{
    /**
     * Handle the incoming FHIR request.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $token = $request->bearerToken();

        if ($token === null) {
            return $this->reject('error', 'login', 'A bearer token is required for FHIR access.', 401);
        }

        $accessToken = PersonalAccessToken::findToken($token);

        if ($accessToken === null || $accessToken->tokenable_type !== ApiClient::class) {
            return $this->reject('error', 'invalid-token', 'The provided token is not valid for FHIR access.', 401);
        }

        $client = $accessToken->tokenable;

        if (! $client instanceof ApiClient || ! $client->is_active) {
            return $this->reject('error', 'unknown', 'The API client is inactive.', 403);
        }

        if (! $accessToken->can(ApiClient::FHIR_READ_ABILITY)) {
            return $this->reject('error', 'forbidden', 'The token does not carry the fhir.read ability.', 403);
        }

        $request->attributes->set('api_client', $client);

        $response = $next($request);

        ApiAccessLog::create([
            'api_client_id' => $client->id,
            'method' => $request->method(),
            'path' => $request->path(),
            'status_code' => $response->getStatusCode(),
        ]);

        $client->forceFill(['last_used_at' => now()])->save();

        return $response;
    }

    /**
     * Build a FHIR OperationOutcome rejection response.
     */
    protected function reject(string $severity, string $code, string $diagnostics, int $status): mixed
    {
        return response()->json(FhirBundle::outcome($severity, $code, $diagnostics), $status);
    }
}
