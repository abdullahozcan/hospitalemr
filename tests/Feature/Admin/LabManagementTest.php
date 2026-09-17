<?php

use App\Enums\LabOrderStatus;
use App\Enums\LabResultFlag;
use App\Enums\TeamRole;
use App\Enums\UserRole;
use App\Filament\Resources\LabOrders\Pages\CreateLabOrder;
use App\Filament\Resources\LabOrders\Pages\ListLabOrders;
use App\Models\Encounter;
use App\Models\LabOrder;
use App\Models\LabResult;
use App\Models\LabTest;
use App\Models\Patient;
use App\Models\Team;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Livewire\Livewire;

beforeEach(fn () => $this->seed(RolePermissionSeeder::class));

test('lab queries are scoped to the current team', function () {
    $teamA = Team::factory()->create();
    $teamB = Team::factory()->create();

    $own = LabOrder::factory()->create(['team_id' => $teamA->id]);
    LabOrder::factory()->create(['team_id' => $teamB->id]);

    $user = User::factory()->create();
    $teamA->members()->attach($user, ['role' => TeamRole::Member->value]);
    $user->switchTeam($teamA);
    $this->actingAs($user);

    expect(LabOrder::all()->pluck('id')->all())->toBe([$own->id]);
});

test('doctors can create lab orders with multiple tests', function () {
    $doctor = User::factory()->withRole(UserRole::Doctor)->create();
    $this->actingAs($doctor);

    $patient = Patient::factory()->create(['team_id' => $doctor->current_team_id]);
    $tests = LabTest::factory()->count(2)->create(['team_id' => $doctor->current_team_id]);

    Livewire::test(CreateLabOrder::class)
        ->fillForm([
            'patient_id' => $patient->id,
            'ordered_at' => now()->format('Y-m-d H:i:s'),
            'lab_test_ids' => $tests->pluck('id')->all(),
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $order = LabOrder::query()->where('patient_id', $patient->id)->first();

    expect($order)->not->toBeNull()
        ->and($order->status)->toBe(LabOrderStatus::Ordered)
        ->and($order->results()->count())->toBe(2)
        ->and($order->ordered_by)->toBe($doctor->id);
});

test('lab technicians can record but not review results', function () {
    $tech = User::factory()->withRole(UserRole::LabTechnician)->create();
    $order = LabOrder::factory()->create();
    $result = LabResult::factory()->create([
        'lab_order_id' => $order->id,
        'lab_test_id' => LabTest::factory()->create(),
        'flag' => LabResultFlag::High,
    ]);

    $this->actingAs($tech);

    expect($tech->can('recordResult', $order))->toBeTrue()
        ->and($tech->can('review', $order))->toBeFalse()
        ->and($result->is_abnormal)->toBeTrue();
});

test('doctors can review resulted orders', function () {
    $doctor = User::factory()->withRole(UserRole::Doctor)->create();
    $order = LabOrder::factory()->create(['status' => LabOrderStatus::Resulted]);

    $this->actingAs($doctor);

    expect($doctor->can('review', $order))->toBeTrue();
});

test('accountants cannot see lab orders', function () {
    $user = User::factory()->withRole(UserRole::Accountant)->create();

    expect($user->can('viewAny', LabOrder::class))->toBeFalse();
});

test('lab order status workflow is enforced', function () {
    $order = LabOrder::factory()->create(['status' => LabOrderStatus::Ordered]);

    expect($order->status->nextStatuses())->toContain(LabOrderStatus::Collected)
        ->and(LabOrderStatus::Reviewed->nextStatuses())->toBe([]);
});

test('doctors can mark orders as collected through the panel', function () {
    $doctor = User::factory()->withRole(UserRole::Doctor)->create();
    $patient = Patient::factory()->create(['team_id' => $doctor->current_team_id]);
    $order = LabOrder::factory()->create([
        'team_id' => $doctor->current_team_id,
        'patient_id' => $patient->id,
        'status' => LabOrderStatus::Ordered,
    ]);

    $this->actingAs($doctor);

    Livewire::test(ListLabOrders::class)
        ->callTableAction('lab_collected', $order);

    expect($order->refresh()->status)->toBe(LabOrderStatus::Collected)
        ->and($order->sample_collected_at)->not->toBeNull();
});

test('lab orders can be linked to encounters', function () {
    $encounter = Encounter::factory()->create();
    $order = LabOrder::factory()->create([
        'team_id' => $encounter->team_id,
        'encounter_id' => $encounter->id,
    ]);

    expect($order->encounter->id)->toBe($encounter->id);
});

test('pending scope captures only in-flight orders', function () {
    $user = User::factory()->withRole(UserRole::Admin)->create();
    $this->actingAs($user);

    $pending = LabOrder::factory()->create([
        'team_id' => $user->current_team_id,
        'status' => LabOrderStatus::InLaboratory,
    ]);
    LabOrder::factory()->create([
        'team_id' => $user->current_team_id,
        'status' => LabOrderStatus::Reviewed,
    ]);

    expect(LabOrder::query()->pending()->pluck('id')->all())->toBe([$pending->id]);
});

test('lab tests manage active status', function () {
    $active = LabTest::factory()->create();
    $inactive = LabTest::factory()->inactive()->create();

    expect($active->is_active)->toBeTrue()
        ->and($inactive->is_active)->toBeFalse();
});
