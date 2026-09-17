<?php

use App\Enums\Gender;
use App\Enums\TeamRole;
use App\Enums\UserRole;
use App\Filament\Resources\Patients\Pages\CreatePatient;
use App\Filament\Resources\Patients\Pages\EditPatient;
use App\Filament\Resources\Patients\Pages\ListPatients;
use App\Filament\Resources\Patients\Pages\ViewPatient;
use App\Models\InsurancePolicy;
use App\Models\Patient;
use App\Models\PatientDocument;
use App\Models\Team;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Livewire\Livewire;

beforeEach(fn () => $this->seed(RolePermissionSeeder::class));

test('patient queries are scoped to the current team', function () {
    $teamA = Team::factory()->create();
    $teamB = Team::factory()->create();

    $patientA = Patient::factory()->create(['team_id' => $teamA->id]);
    Patient::factory()->create(['team_id' => $teamB->id]);

    $user = User::factory()->create();
    $teamA->members()->attach($user, ['role' => TeamRole::Member->value]);
    $user->switchTeam($teamA);
    $this->actingAs($user);

    expect(Patient::all()->pluck('id')->all())->toBe([$patientA->id]);
});

test('a medical record number is generated automatically', function () {
    $patient = Patient::factory()->create();

    expect($patient->mrn)->toStartWith('MRN-')
        ->and(strlen($patient->mrn))->toBe(12);
});

test('medical record numbers are unique', function () {
    $a = Patient::factory()->create()->mrn;
    $b = Patient::factory()->create()->mrn;

    expect($a)->not->toBe($b);
});

test('the full name and age accessors work', function () {
    $patient = Patient::factory()->create([
        'first_name' => 'Ali',
        'last_name' => 'Veli',
        'birth_date' => now()->subYears(40),
    ]);

    expect($patient->full_name)->toBe('Ali Veli')
        ->and($patient->age)->toBe(40);
});

test('doctors can view the patient list', function () {
    $user = User::factory()->withRole(UserRole::Doctor)->create();

    $this->actingAs($user);

    Livewire::test(ListPatients::class)->assertSuccessful();
});

test('accountants cannot create patients through the panel', function () {
    $user = User::factory()->withRole(UserRole::Accountant)->create();

    $this->actingAs($user);

    Livewire::test(ListPatients::class)->assertSuccessful();

    expect($user->can('create', Patient::class))->toBeFalse();
});

test('receptionists can create patients through the panel', function () {
    $user = User::factory()->withRole(UserRole::Receptionist)->create();
    $this->actingAs($user);

    Livewire::test(CreatePatient::class)
        ->fillForm([
            'first_name' => 'Ayşe',
            'last_name' => 'Yılmaz',
            'birth_date' => '1990-05-05',
            'gender' => Gender::Female->value,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $patient = Patient::query()->where('first_name', 'Ayşe')->first();

    expect($patient)->not->toBeNull()
        ->and($patient->team_id)->toBe($user->current_team_id)
        ->and($patient->mrn)->toStartWith('MRN-');
});

test('doctors can edit patients through the panel', function () {
    $user = User::factory()->withRole(UserRole::Doctor)->create();
    $patient = Patient::factory()->create(['team_id' => $user->current_team_id]);

    $this->actingAs($user);

    Livewire::test(EditPatient::class, ['record' => $patient->getKey()])
        ->fillForm([
            'phone' => '+90 555 555 55 55',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($patient->refresh()->phone)->toBe('+90 555 555 55 55');
});

test('doctors can view a patient record with related data', function () {
    $user = User::factory()->withRole(UserRole::Doctor)->create();
    $patient = Patient::factory()->create(['team_id' => $user->current_team_id]);
    $policy = InsurancePolicy::factory()->create([
        'patient_id' => $patient->id,
        'team_id' => $user->current_team_id,
    ]);
    $document = PatientDocument::factory()->create([
        'patient_id' => $patient->id,
        'team_id' => $user->current_team_id,
    ]);

    $this->actingAs($user);

    Livewire::test(ViewPatient::class, ['record' => $patient->getKey()])->assertSuccessful();

    expect($patient->insurancePolicies()->where('id', $policy->id)->exists())->toBeTrue()
        ->and($patient->documents()->where('id', $document->id)->exists())->toBeTrue();
});

test('patients from other teams are not listed', function () {
    $user = User::factory()->withRole(UserRole::Admin)->create();
    $ownPatient = Patient::factory()->create(['team_id' => $user->current_team_id]);
    Patient::factory()->create();

    $this->actingAs($user);

    Livewire::test(ListPatients::class)->assertCanSeeTableRecords([$ownPatient]);
});
