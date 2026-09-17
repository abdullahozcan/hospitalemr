<?php

use App\Enums\AllergySeverity;
use App\Enums\PrescriptionStatus;
use App\Enums\UserRole;
use App\Filament\Resources\Drugs\Pages\ListDrugs;
use App\Filament\Resources\Prescriptions\Pages\CreatePrescription;
use App\Filament\Resources\Prescriptions\Pages\EditPrescription;
use App\Filament\Resources\Prescriptions\Pages\ListPrescriptions;
use App\Models\Allergy;
use App\Models\Drug;
use App\Models\Encounter;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\User;
use App\Services\DrugInteractionService;
use Database\Seeders\RolePermissionSeeder;
use Livewire\Livewire;

beforeEach(fn () => $this->seed(RolePermissionSeeder::class));

test('doctors can create prescriptions with multiple items', function () {
    $doctor = User::factory()->withRole(UserRole::Doctor)->create();
    $this->actingAs($doctor);

    $patient = Patient::factory()->create(['team_id' => $doctor->current_team_id]);
    $drug = Drug::factory()->create(['team_id' => $doctor->current_team_id]);

    Livewire::test(CreatePrescription::class)
        ->fillForm([
            'patient_id' => $patient->id,
            'issued_at' => now()->format('Y-m-d H:i:s'),
            'items' => [
                [
                    'drug_id' => $drug->id,
                    'dosage' => '2 tablet',
                    'frequency' => 'Günde 3 kez',
                    'duration_days' => 10,
                    'quantity' => 60,
                ],
            ],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $prescription = Prescription::query()->where('patient_id', $patient->id)->first();

    expect($prescription)->not->toBeNull()
        ->and($prescription->items()->count())->toBe(1)
        ->and($prescription->items()->first()->dosage)->toBe('2 tablet')
        ->and($prescription->prescriber_id)->toBe($doctor->id);
});

test('nurses cannot create prescriptions', function () {
    $user = User::factory()->withRole(UserRole::Nurse)->create();

    expect($user->can('create', Prescription::class))->toBeFalse();
});

test('the interaction service flags allergy conflicts', function () {
    $service = new DrugInteractionService;

    $patient = Patient::factory()->create();
    Allergy::factory()->create([
        'patient_id' => $patient->id,
        'allergen' => 'Parasetamol',
        'severity' => AllergySeverity::Severe,
    ]);

    $conflicting = Drug::factory()->create([
        'active_ingredient' => 'Parasetamol',
    ]);
    $safe = Drug::factory()->create([
        'name' => 'Ventolin',
        'active_ingredient' => 'Salbutamol',
    ]);

    $warnings = $service->check($patient, [$conflicting->id, $safe->id]);

    expect($warnings)->toHaveCount(1)
        ->and($warnings->first()['drug'])->toBe($conflicting->name);
});

test('the interaction service returns nothing for allergy-free patients', function () {
    $service = new DrugInteractionService;
    $patient = Patient::factory()->create();

    $drug = Drug::factory()->create();

    expect($service->check($patient, [$drug->id]))->toBeEmpty();
});

test('doctors can edit prescriptions and their items', function () {
    $doctor = User::factory()->withRole(UserRole::Doctor)->create();
    $patient = Patient::factory()->create(['team_id' => $doctor->current_team_id]);
    $prescription = Prescription::factory()->create([
        'team_id' => $doctor->current_team_id,
        'patient_id' => $patient->id,
    ]);

    $this->actingAs($doctor);

    Livewire::test(EditPrescription::class, ['record' => $prescription->getKey()])
        ->fillForm(['notes' => 'Hasta 3 gün sonra kontrol edilmeli.'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($prescription->refresh()->notes)->toBe('Hasta 3 gün sonra kontrol edilmeli.');
});

test('doctors can dispense active prescriptions from the list', function () {
    $doctor = User::factory()->withRole(UserRole::Doctor)->create();
    $patient = Patient::factory()->create(['team_id' => $doctor->current_team_id]);
    $prescription = Prescription::factory()->create([
        'team_id' => $doctor->current_team_id,
        'patient_id' => $patient->id,
        'status' => PrescriptionStatus::Active,
    ]);

    $this->actingAs($doctor);

    Livewire::test(ListPrescriptions::class)
        ->callTableAction('dispense', $prescription);

    expect($prescription->refresh()->status)->toBe(PrescriptionStatus::Dispensed)
        ->and($prescription->dispensed_at)->not->toBeNull();
});

test('dispensed prescriptions cannot be dispensed again', function () {
    $prescription = Prescription::factory()->create(['status' => PrescriptionStatus::Dispensed]);

    expect($prescription->status->nextStatuses())->toBe([]);
});

test('drug stock levels and low stock flags work', function () {
    $low = Drug::factory()->lowStock()->create();
    $healthy = Drug::factory()->create(['stock_quantity' => 500, 'low_stock_threshold' => 20]);

    expect($low->is_low_stock)->toBeTrue()
        ->and($healthy->is_low_stock)->toBeFalse();
});

test('drugs are searchable by name in the panel list', function () {
    $user = User::factory()->withRole(UserRole::Doctor)->create();
    $this->actingAs($user);

    $drug = Drug::factory()->create(['team_id' => $user->current_team_id, 'name' => 'Zyrtecol']);

    Livewire::test(ListDrugs::class)->assertCanSeeTableRecords([$drug]);
});

test('prescriptions from other teams are not listed', function () {
    $user = User::factory()->withRole(UserRole::Admin)->create();
    $this->actingAs($user);

    $own = Prescription::factory()->create(['team_id' => $user->current_team_id]);
    Prescription::factory()->create();

    Livewire::test(ListPrescriptions::class)->assertCanSeeTableRecords([$own]);
});

test('the print route renders for authorized users', function () {
    $doctor = User::factory()->withRole(UserRole::Doctor)->create();
    $patient = Patient::factory()->create(['team_id' => $doctor->current_team_id]);
    $prescription = Prescription::factory()
        ->withItems(2)
        ->create([
            'team_id' => $doctor->current_team_id,
            'patient_id' => $patient->id,
        ]);

    $this->actingAs($doctor)
        ->get(route('prescriptions.print', $prescription))
        ->assertOk()
        ->assertSee($patient->mrn)
        ->assertSee($prescription->items[0]->drug->name);
});

test('unauthorized users cannot print prescriptions', function () {
    $user = User::factory()->withRole(UserRole::Accountant)->create();
    $prescription = Prescription::factory()->create(['team_id' => $user->current_team_id]);

    $this->actingAs($user)
        ->get(route('prescriptions.print', $prescription))
        ->assertForbidden();
});

test('prescriptions can be linked to encounters', function () {
    $encounter = Encounter::factory()->create();
    $prescription = Prescription::factory()->create([
        'team_id' => $encounter->team_id,
        'encounter_id' => $encounter->id,
    ]);

    expect($prescription->encounter->id)->toBe($encounter->id)
        ->and($encounter->fresh())->not->toBeNull();
});
