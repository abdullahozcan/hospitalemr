<?php

use App\Enums\ClaimStatus;
use App\Enums\PaymentMethod;
use App\Enums\TeamRole;
use App\Enums\UserRole;
use App\Filament\Resources\Claims\Pages\ListClaims;
use App\Filament\Resources\Services\Pages\ListServices;
use App\Models\BillingLine;
use App\Models\Claim;
use App\Models\Encounter;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Service;
use App\Models\Team;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Livewire\Livewire;

beforeEach(fn () => $this->seed(RolePermissionSeeder::class));

test('billing queries are scoped to the current team', function () {
    $teamA = Team::factory()->create();
    $teamB = Team::factory()->create();

    $own = BillingLine::factory()->create(['team_id' => $teamA->id]);
    BillingLine::factory()->create(['team_id' => $teamB->id]);

    $user = User::factory()->create();
    $teamA->members()->attach($user, ['role' => TeamRole::Member->value]);
    $user->switchTeam($teamA);
    $this->actingAs($user);

    expect(BillingLine::all()->pluck('id')->all())->toBe([$own->id]);
});

test('billing line totals include discounts', function () {
    $line = BillingLine::factory()->create([
        'quantity' => 2,
        'unit_price' => 500,
        'discount_amount' => 100,
    ]);

    expect($line->total)->toBe(900.0);
});

test('patient balance is billed minus paid', function () {
    $user = User::factory()->withRole(UserRole::Accountant)->create();
    $patient = Patient::factory()->create(['team_id' => $user->current_team_id]);
    $this->actingAs($user);

    BillingLine::factory()->count(2)->create([
        'team_id' => $user->current_team_id,
        'patient_id' => $patient->id,
        'quantity' => 1,
        'unit_price' => 500,
        'discount_amount' => 0,
    ]);

    Payment::factory()->create([
        'team_id' => $user->current_team_id,
        'patient_id' => $patient->id,
        'amount' => 300,
    ]);

    expect($patient->refresh()->balance)->toBe(700.0);
});

test('accountants can create payments', function () {
    $user = User::factory()->withRole(UserRole::Accountant)->create();

    expect($user->can('create', Payment::class))->toBeTrue()
        ->and($user->can('update', new Payment))->toBeTrue();
});

test('receptionists can collect but not edit payments', function () {
    $user = User::factory()->withRole(UserRole::Receptionist)->create();

    expect($user->can('create', Payment::class))->toBeTrue()
        ->and($user->can('update', new Payment))->toBeFalse();
});

test('nurses cannot view billing', function () {
    $user = User::factory()->withRole(UserRole::Nurse)->create();

    expect($user->can('viewAny', BillingLine::class))->toBeFalse();
});

test('doctors can bill but not collect payments', function () {
    $user = User::factory()->withRole(UserRole::Doctor)->create();

    expect($user->can('create', BillingLine::class))->toBeTrue()
        ->and($user->can('create', Payment::class))->toBeFalse();
});

test('accountants can manage the service catalog', function () {
    $user = User::factory()->withRole(UserRole::Accountant)->create();

    expect($user->can('create', Service::class))->toBeTrue()
        ->and($user->can('delete', new Service))->toBeFalse();
});

test('accountants can see the billing list through the panel', function () {
    $user = User::factory()->withRole(UserRole::Accountant)->create();
    $this->actingAs($user);

    $line = BillingLine::factory()->create(['team_id' => $user->current_team_id]);

    Livewire::test(ListServices::class)->assertSuccessful();

    Livewire::test(ListServices::class)->assertCanSeeTableRecords(
        Service::query()->whereKey($line->service_id)->get(),
    );
});

test('payments are listed with running totals', function () {
    $user = User::factory()->withRole(UserRole::Accountant)->create();
    $this->actingAs($user);

    $payment = Payment::factory()->create([
        'team_id' => $user->current_team_id,
        'amount' => 750,
    ]);

    Livewire::test(ListServices::class)->assertSuccessful();
    Livewire::test(ListServices::class);

    expect($payment->amount)->toBe('750.00');
});

test('claims transition through their status workflow', function () {
    $claim = Claim::factory()->create(['status' => ClaimStatus::Draft]);

    expect($claim->status->nextStatuses())->toBe([ClaimStatus::Submitted])
        ->and(ClaimStatus::Paid->nextStatuses())->toBe([]);
});

test('accountants can submit claims through the panel', function () {
    $user = User::factory()->withRole(UserRole::Accountant)->create();
    $patient = Patient::factory()->create(['team_id' => $user->current_team_id]);
    $claim = Claim::factory()->create([
        'team_id' => $user->current_team_id,
        'patient_id' => $patient->id,
        'status' => ClaimStatus::Draft,
    ]);

    $this->actingAs($user);

    Livewire::test(ListClaims::class)
        ->callTableAction('claim_submitted', $claim);

    expect($claim->refresh()->status)->toBe(ClaimStatus::Submitted)
        ->and($claim->submitted_at)->not->toBeNull();
});

test('claims from other teams are not listed', function () {
    $user = User::factory()->withRole(UserRole::Accountant)->create();
    $this->actingAs($user);

    $own = Claim::factory()->create(['team_id' => $user->current_team_id]);
    Claim::factory()->create();

    Livewire::test(ListClaims::class)->assertCanSeeTableRecords([$own]);
});

test('insurance payments are tagged by method', function () {
    $user = User::factory()->withRole(UserRole::Accountant)->create();
    $this->actingAs($user);

    $cash = Payment::factory()->create([
        'team_id' => $user->current_team_id,
        'method' => PaymentMethod::Cash,
    ]);
    $insurance = Payment::factory()->create([
        'team_id' => $user->current_team_id,
        'method' => PaymentMethod::Insurance,
    ]);

    expect(Payment::query()->insurance()->pluck('id')->all())->toBe([$insurance->id])
        ->and(Payment::query()->insurance()->pluck('id'))->not->toContain($cash->id);
});

test('billing lines can reference encounters for summaries', function () {
    $encounter = Encounter::factory()->create();

    BillingLine::factory()->count(2)->create([
        'team_id' => $encounter->team_id,
        'patient_id' => $encounter->patient_id,
        'encounter_id' => $encounter->id,
    ]);

    expect($encounter->billingLines()->count())->toBe(2);
});
