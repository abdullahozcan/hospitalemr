<?php

use App\Enums\LabOrderStatus;
use App\Enums\PrescriptionStatus;
use App\Enums\UserRole;
use App\Jobs\SyncEncounterToThs;
use App\Jobs\SyncLabOrderToThs;
use App\Jobs\SyncPrescriptionToThs;
use App\Models\Encounter;
use App\Models\IntegrationLog;
use App\Models\LabOrder;
use App\Models\LabResult;
use App\Models\LabTest;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\User;
use App\Ths\MockThsService;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Support\Facades\Queue;

beforeEach(fn () => $this->seed(RolePermissionSeeder::class));

test('the mock service sends patients with a national id', function () {
    $patient = Patient::factory()->create();

    $response = (new MockThsService)->sendPatient($patient);

    expect($response->success)->toBeTrue()
        ->and($response->code)->toBe('ENB-PAT-200')
        ->and($response->raw['enabiz_patient_id'])->not->toBeEmpty();
});

test('the mock service rejects patients without a national id', function () {
    $patient = Patient::factory()->create(['national_id' => null]);

    $response = (new MockThsService)->sendPatient($patient);

    expect($response->success)->toBeFalse()
        ->and($response->code)->toBe('ENB-PAT-400');
});

test('the mock service rejects incomplete encounters', function () {
    $encounter = Encounter::factory()->create(['ended_at' => null]);

    $response = (new MockThsService)->sendEncounter($encounter);

    expect($response->success)->toBeFalse()
        ->and($response->code)->toBe('ENB-ENC-400');
});

test('the mock service sends completed encounters with a provider', function () {
    $encounter = Encounter::factory()->completed()->create();

    $response = (new MockThsService)->sendEncounter($encounter);

    expect($response->success)->toBeFalse()
        ->and($response->code)->toBe('ENB-ENC-401');

    $encounter->update(['provider_id' => User::factory()->create()->id]);

    $response = (new MockThsService)->sendEncounter($encounter);

    expect($response->success)->toBeTrue()
        ->and($response->raw['submission_id'])->toStartWith('SUB-');
});

test('the mock service sends reviewed lab orders', function () {
    $order = LabOrder::factory()->status(LabOrderStatus::Reviewed)->create();
    $result = LabResult::factory()->create([
        'lab_order_id' => $order->id,
        'lab_test_id' => LabTest::factory()->create(),
    ]);

    $response = (new MockThsService)->sendLabResults($order);

    expect($response->success)->toBeTrue()
        ->and($response->raw['result_count'])->toBe(1);
});

test('the mock service sends prescriptions to sgk and marks them dispensed', function () {
    $prescription = Prescription::factory()->withItems(1)->create();

    $response = (new MockThsService)->sendPrescription($prescription);

    expect($response->success)->toBeTrue()
        ->and($response->code)->toBe('SGK-RX-200')
        ->and($prescription->refresh()->status)->toBe(PrescriptionStatus::Dispensed);
});

test('completing an encounter queues an e-nabiz submission', function () {
    Queue::fake();

    $encounter = Encounter::factory()->create(['ended_at' => null]);
    $encounter->update(['ended_at' => now()]);

    Queue::assertPushed(SyncEncounterToThs::class);
});

test('reviewing a lab order queues an e-nabiz submission', function () {
    Queue::fake();

    $order = LabOrder::factory()->status(LabOrderStatus::Resulted)->create();
    $order->update(['status' => LabOrderStatus::Reviewed]);

    Queue::assertPushed(SyncLabOrderToThs::class);
});

test('activating a prescription queues an sgk submission', function () {
    Queue::fake();

    $prescription = Prescription::factory()->create(['status' => PrescriptionStatus::Draft]);
    $prescription->update(['status' => PrescriptionStatus::Active]);

    Queue::assertPushed(SyncPrescriptionToThs::class);
});

test('the sync job records a successful integration log', function () {
    $encounter = Encounter::factory()->completed()->create(['provider_id' => User::factory()->create()->id]);

    SyncEncounterToThs::dispatch($encounter);

    $log = IntegrationLog::query()->first();

    expect($log->status)->toBe(IntegrationLog::STATUS_SUCCESS)
        ->and($log->service)->toBe(IntegrationLog::SERVICE_ENABIZ)
        ->and($log->attempts)->toBe(1)
        ->and($log->sent_at)->not->toBeNull();
});

test('the sync job records failed integrations with the response code', function () {
    $encounter = Encounter::factory()->completed()->create();
    $encounter->update(['ended_at' => now()]);

    SyncEncounterToThs::dispatch($encounter);

    $log = IntegrationLog::query()->first();

    expect($log->status)->toBe(IntegrationLog::STATUS_FAILED)
        ->and($log->code)->toBe('ENB-ENC-401');
});

test('prescription submissions log to the sgk service', function () {
    $prescription = Prescription::factory()->withItems(1)->create();

    SyncPrescriptionToThs::dispatch($prescription);

    $log = IntegrationLog::query()->first();

    expect($log->service)->toBe(IntegrationLog::SERVICE_SGK)
        ->and($log->status)->toBe(IntegrationLog::STATUS_SUCCESS);
});

test('disabling ths skips the sync job', function () {
    config()->set('ths.enabled', false);

    $encounter = Encounter::factory()->completed()->create(['provider_id' => User::factory()->create()->id]);

    SyncEncounterToThs::dispatch($encounter);

    expect(IntegrationLog::count())->toBe(0);
});

test('only admins can view integration logs', function () {
    $admin = User::factory()->withRole(UserRole::Admin)->create();
    $doctor = User::factory()->withRole(UserRole::Doctor)->create();

    expect($admin->can('viewAny', IntegrationLog::class))->toBeTrue()
        ->and($doctor->can('viewAny', IntegrationLog::class))->toBeFalse();
});
