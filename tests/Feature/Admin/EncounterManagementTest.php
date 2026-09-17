<?php

use App\Enums\AllergySeverity;
use App\Enums\TeamRole;
use App\Enums\UserRole;
use App\Filament\Resources\Encounters\Pages\CreateEncounter;
use App\Filament\Resources\Encounters\Pages\EditEncounter;
use App\Filament\Resources\Encounters\Pages\ListEncounters;
use App\Filament\Resources\Encounters\Pages\ViewEncounter;
use App\Models\Allergy;
use App\Models\Encounter;
use App\Models\IcdCode;
use App\Models\Immunization;
use App\Models\Patient;
use App\Models\Team;
use App\Models\User;
use App\Models\Vital;
use Database\Seeders\IcdCodeSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Support\Carbon;
use Livewire\Livewire;

beforeEach(fn () => $this->seed(RolePermissionSeeder::class));

test('encounter queries are scoped to the current team', function () {
    $teamA = Team::factory()->create();
    $teamB = Team::factory()->create();

    $encounterA = Encounter::factory()->create(['team_id' => $teamA->id]);
    Encounter::factory()->create(['team_id' => $teamB->id]);

    $user = User::factory()->create();
    $teamA->members()->attach($user, ['role' => TeamRole::Member->value]);
    $user->switchTeam($teamA);
    $this->actingAs($user);

    expect(Encounter::all()->pluck('id')->all())->toBe([$encounterA->id]);
});

test('doctors can create encounters through the panel', function () {
    $doctor = User::factory()->withRole(UserRole::Doctor)->create();
    $this->actingAs($doctor);

    $patient = Patient::factory()->create(['team_id' => $doctor->current_team_id]);

    Livewire::test(CreateEncounter::class)
        ->fillForm([
            'patient_id' => $patient->id,
            'started_at' => now()->format('Y-m-d H:i:s'),
            'chief_complaint' => 'Baş ağrısı',
            'subjective' => 'İki gündür baş ağrısı var.',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $encounter = Encounter::query()->where('chief_complaint', 'Baş ağrısı')->first();

    expect($encounter)->not->toBeNull()
        ->and($encounter->team_id)->toBe($doctor->current_team_id)
        ->and($encounter->subjective)->toBe('İki gündür baş ağrısı var.');
});

test('nurses can create encounters but not delete them', function () {
    $user = User::factory()->withRole(UserRole::Nurse)->create();

    expect($user->can('create', Encounter::class))->toBeTrue()
        ->and($user->can('delete', new Encounter))->toBeFalse();
});

test('accountants cannot view encounters', function () {
    $user = User::factory()->withRole(UserRole::Accountant)->create();

    expect($user->can('viewAny', Encounter::class))->toBeFalse();
});

test('doctors can edit the soap note through the panel', function () {
    $doctor = User::factory()->withRole(UserRole::Doctor)->create();
    $patient = Patient::factory()->create(['team_id' => $doctor->current_team_id]);
    $encounter = Encounter::factory()->create([
        'team_id' => $doctor->current_team_id,
        'patient_id' => $patient->id,
    ]);

    $this->actingAs($doctor);

    Livewire::test(EditEncounter::class, ['record' => $encounter->getKey()])
        ->fillForm([
            'plan' => 'Dinlenme ve sıvı alımı önerildi.',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($encounter->refresh()->plan)->toBe('Dinlenme ve sıvı alımı önerildi.');
});

test('encounters can be linked to ICD-10 diagnoses', function () {
    $this->seed(IcdCodeSeeder::class);

    $encounter = Encounter::factory()->create();
    $icd = IcdCode::query()->where('code', 'J45')->first();

    $encounter->diagnoses()->attach($icd->id, ['is_primary' => true]);

    expect($encounter->diagnoses()->where('icd_code_id', $icd->id)->exists())->toBeTrue()
        ->and((bool) $encounter->diagnoses()->first()->pivot->is_primary)->toBeTrue();
});

test('vitals record BMI correctly', function () {
    $vital = Vital::factory()->create([
        'height_cm' => 180,
        'weight_grams' => 80000,
    ]);

    expect($vital->bmi)->toBe(24.7);
});

test('doctors can record vitals on an encounter view', function () {
    $doctor = User::factory()->withRole(UserRole::Doctor)->create();
    $patient = Patient::factory()->create(['team_id' => $doctor->current_team_id]);
    $encounter = Encounter::factory()->create([
        'team_id' => $doctor->current_team_id,
        'patient_id' => $patient->id,
    ]);

    $this->actingAs($doctor);

    Livewire::test(ViewEncounter::class, ['record' => $encounter->getKey()])->assertSuccessful();

    $vital = Vital::factory()->create([
        'team_id' => $doctor->current_team_id,
        'encounter_id' => $encounter->id,
    ]);

    expect($encounter->vitals()->where('id', $vital->id)->exists())->toBeTrue();
});

test('patient allergies can be recorded with severity', function () {
    $patient = Patient::factory()->create();

    $allergy = Allergy::factory()->create([
        'patient_id' => $patient->id,
        'severity' => AllergySeverity::Severe,
    ]);

    expect($allergy->severity)->toBe(AllergySeverity::Severe)
        ->and($patient->allergies()->count())->toBe(1);
});

test('patient immunization history is tracked', function () {
    $patient = Patient::factory()->create();

    Immunization::factory()->count(2)->create([
        'patient_id' => $patient->id,
        'administered_at' => Carbon::parse('2024-01-15'),
    ]);

    expect($patient->immunizations()->count())->toBe(2);
});

test('doctors can view the encounter list', function () {
    $user = User::factory()->withRole(UserRole::Doctor)->create();
    $this->actingAs($user);

    $encounter = Encounter::factory()->create(['team_id' => $user->current_team_id]);

    Livewire::test(ListEncounters::class)->assertCanSeeTableRecords([$encounter]);
});

test('patients from other teams do not leak into the encounter list', function () {
    $user = User::factory()->withRole(UserRole::Admin)->create();
    $this->actingAs($user);

    $ownEncounter = Encounter::factory()->create(['team_id' => $user->current_team_id]);
    Encounter::factory()->create();

    Livewire::test(ListEncounters::class)->assertCanSeeTableRecords([$ownEncounter]);
});
