<?php

use App\Enums\UserRole;
use App\Fhir\MedicationRequestMapper;
use App\Fhir\ObservationMapper;
use App\Fhir\PatientMapper;
use App\Models\ApiAccessLog;
use App\Models\ApiClient;
use App\Models\Encounter;
use App\Models\Immunization;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\Team;
use App\Models\User;
use App\Models\Vital;
use Database\Seeders\RolePermissionSeeder;

beforeEach(fn () => $this->seed(RolePermissionSeeder::class));

test('fhir requests without a token are rejected', function () {
    $this->getJson('/api/fhir/Patient')
        ->assertStatus(401)
        ->assertJsonStructure(['resourceType', 'issue']);
});

test('invalid tokens are rejected', function () {
    $this->getJson('/api/fhir/Patient', ['Authorization' => 'Bearer invalid-token'])
        ->assertStatus(401);
});

test('inactive clients are rejected', function () {
    $client = ApiClient::factory()->inactive()->create();
    $token = $client->createToken('test', [ApiClient::FHIR_READ_ABILITY]);

    $this->getJson('/api/fhir/Patient', ['Authorization' => 'Bearer '.$token->plainTextToken])
        ->assertStatus(403);
});

test('tokens without the fhir.read ability are rejected', function () {
    $client = ApiClient::factory()->create();
    $token = $client->createToken('test', []);

    $this->getJson('/api/fhir/Patient', ['Authorization' => 'Bearer '.$token->plainTextToken])
        ->assertStatus(403);
});

test('a patient is mapped to a FHIR R4 resource', function () {
    $patient = Patient::factory()->create([
        'first_name' => 'Ayşe',
        'last_name' => 'Yılmaz',
    ]);

    $resource = (new PatientMapper)->toFhir($patient);

    expect($resource['resourceType'])->toBe('Patient')
        ->and($resource['id'])->toBe((string) $patient->id)
        ->and($resource['name'][0]['family'])->toBe('Yılmaz')
        ->and($resource['name'][0]['given'])->toBe(['Ayşe'])
        ->and($resource['identifier'][0]['value'])->toBe($patient->mrn);
});

test('patient resources can be read through the api', function () {
    $client = ApiClient::factory()->create();
    $patient = Patient::factory()->create(['team_id' => $client->team_id]);
    $token = $client->createToken('test', [ApiClient::FHIR_READ_ABILITY]);

    $this->withToken($token->plainTextToken)
        ->getJson('/api/fhir/Patient/'.$patient->id)
        ->assertOk()
        ->assertJson(['resourceType' => 'Patient'])
        ->assertJsonPath('id', (string) $patient->id);
});

test('patients can be searched by mrn', function () {
    $client = ApiClient::factory()->create();
    $patient = Patient::factory()->create(['team_id' => $client->team_id]);
    Patient::factory()->count(2)->create(['team_id' => $client->team_id]);
    $token = $client->createToken('test', [ApiClient::FHIR_READ_ABILITY]);

    $response = $this->withToken($token->plainTextToken)
        ->getJson('/api/fhir/Patient?identifier='.$patient->mrn)
        ->assertOk()
        ->assertJson(['resourceType' => 'Bundle', 'type' => 'searchset']);

    expect($response->json('total'))->toBe(1)
        ->and($response->json('entry.0.resource.id'))->toBe((string) $patient->id);
});

test('encounters are scoped to the api client team', function () {
    $client = ApiClient::factory()->create();
    $otherTeam = Team::factory()->create();
    $ownEncounter = Encounter::factory()->create(['team_id' => $client->team_id]);
    Encounter::factory()->create(['team_id' => $otherTeam->id]);
    $token = $client->createToken('test', [ApiClient::FHIR_READ_ABILITY]);

    $response = $this->withToken($token->plainTextToken)
        ->getJson('/api/fhir/Encounter?patient='.Encounter::query()->where('team_id', $client->team_id)->first()->patient_id)
        ->assertOk();

    expect($response->json('total'))->toBe(1)
        ->and($response->json('entry.0.resource.id'))->toBe((string) $ownEncounter->id);
});

test('vitals are mapped to FHIR observations with LOINC codes', function () {
    $vital = Vital::factory()->create([
        'systolic_mmhg' => 120,
        'diastolic_mmhg' => 80,
        'temperature_c' => 37.0,
    ]);

    $resources = (new ObservationMapper)->toFhir($vital);

    expect(count($resources))->toBeGreaterThanOrEqual(6);

    $systolic = collect($resources)->first(fn ($r) => $r['code']['coding'][0]['code'] === '8480-6');

    expect($systolic['resourceType'])->toBe('Observation')
        ->and($systolic['valueQuantity']['value'])->toBe(120.0)
        ->and($systolic['valueQuantity']['code'])->toBe('mm[Hg]');
});

test('observations are returned as a searchset bundle', function () {
    $client = ApiClient::factory()->create();
    Vital::factory()->count(2)->create(['team_id' => $client->team_id]);
    $token = $client->createToken('test', [ApiClient::FHIR_READ_ABILITY]);

    $response = $this->withToken($token->plainTextToken)
        ->getJson('/api/fhir/Observation')
        ->assertOk();

    expect($response->json('resourceType'))->toBe('Bundle')
        ->and($response->json('total'))->toBeGreaterThan(0)
        ->and($response->json('entry.0.resource.resourceType'))->toBe('Observation');
});

test('prescriptions map to medication requests', function () {
    $prescription = Prescription::factory()->withItems(2)->create();

    $resources = (new MedicationRequestMapper)->toFhir($prescription);

    expect(count($resources))->toBe(2)
        ->and($resources[0]['resourceType'])->toBe('MedicationRequest')
        ->and($resources[0]['status'])->toBe('active')
        ->and($resources[0]['subject']['reference'])->toBe('Patient/'.$prescription->patient_id);
});

test('immunizations are searchable by patient', function () {
    $client = ApiClient::factory()->create();
    $patient = Patient::factory()->create(['team_id' => $client->team_id]);
    Immunization::factory()->count(2)->create([
        'team_id' => $client->team_id,
        'patient_id' => $patient->id,
    ]);
    $token = $client->createToken('test', [ApiClient::FHIR_READ_ABILITY]);

    $response = $this->withToken($token->plainTextToken)
        ->getJson('/api/fhir/Immunization?patient='.$patient->id)
        ->assertOk();

    expect($response->json('total'))->toBe(2)
        ->and($response->json('entry.0.resource.resourceType'))->toBe('Immunization');
});

test('the capability statement is public', function () {
    $this->getJson('/api/fhir/metadata')
        ->assertOk()
        ->assertJson(['resourceType' => 'CapabilityStatement'])
        ->assertJsonPath('fhirVersion', '4.0.1');
});

test('api access is logged', function () {
    $client = ApiClient::factory()->create();
    $patient = Patient::factory()->create();
    $token = $client->createToken('test', [ApiClient::FHIR_READ_ABILITY]);

    $this->withToken($token->plainTextToken)
        ->getJson('/api/fhir/Patient/'.$patient->id);

    expect(ApiAccessLog::query()->where('api_client_id', $client->id)->count())->toBe(1)
        ->and(ApiAccessLog::first()->path)->toBe('api/fhir/Patient/'.$patient->id)
        ->and($client->refresh()->last_used_at)->not->toBeNull();
});

test('non-admin users cannot manage api clients in the panel', function () {
    $user = User::factory()->withRole(UserRole::Doctor)->create();

    expect($user->can('viewAny', ApiClient::class))->toBeFalse();
});
