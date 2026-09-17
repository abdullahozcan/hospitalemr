<?php

use App\Enums\TeamRole;
use App\Enums\UserRole;
use App\Filament\Resources\Patients\Pages\ViewPatient;
use App\Models\Appointment;
use App\Models\BillingLine;
use App\Models\LabOrder;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Prescription;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

beforeEach(fn () => $this->seed(RolePermissionSeeder::class));

function portalUser(): array
{
    $user = User::factory()->create();
    $patient = Patient::factory()->create([
        'team_id' => $user->current_team_id,
    ]);
    $user->update(['patient_id' => $patient->id]);

    return [$patient, $user->fresh()];
}

test('the admin panel is not accessible to patient users', function () {
    [, $user] = portalUser();

    expect($user->isPatient())->toBeTrue()
        ->and($user->canAccessPanel(filament()->getPanel('admin')))->toBeFalse();
});

test('the portal is not accessible to clinic users', function () {
    $user = User::factory()->withRole(UserRole::Admin)->create();

    $this->actingAs($user)->get('/portal/dashboard')->assertRedirect('/login');
});

test('guests are redirected to login', function () {
    $this->get('/portal/dashboard')->assertRedirect('/login');
});

test('patients can view the portal dashboard', function () {
    [$patient, $user] = portalUser();

    $this->actingAs($user)
        ->get('/portal/dashboard')
        ->assertOk()
        ->assertSee($patient->first_name);
});

test('patients can view their appointments', function () {
    [$patient, $user] = portalUser();
    $appointment = Appointment::factory()->create([
        'team_id' => $patient->team_id,
        'patient_id' => $patient->id,
    ]);

    $this->actingAs($user)
        ->get('/portal/appointments')
        ->assertOk();
});

test('patients cannot see other patients data in the portal', function () {
    [$patient, $user] = portalUser();
    $otherPatient = Patient::factory()->create();

    Prescription::factory()->withItems(1)->create([
        'team_id' => $otherPatient->team_id,
        'patient_id' => $otherPatient->id,
    ]);

    $this->actingAs($user);

    expect($user->patient->prescriptions()->count())->toBe(0);
});

test('patients can view their prescriptions', function () {
    [$patient, $user] = portalUser();

    Prescription::factory()->withItems(2)->create([
        'team_id' => $patient->team_id,
        'patient_id' => $patient->id,
    ]);

    $this->actingAs($user)
        ->get('/portal/prescriptions')
        ->assertOk();
});

test('patients can view their lab results', function () {
    [$patient, $user] = portalUser();

    LabOrder::factory()->create([
        'team_id' => $patient->team_id,
        'patient_id' => $patient->id,
    ]);

    $this->actingAs($user)
        ->get('/portal/lab-results')
        ->assertOk();
});

test('patients can view their invoices', function () {
    [$patient, $user] = portalUser();

    BillingLine::factory()->create([
        'team_id' => $patient->team_id,
        'patient_id' => $patient->id,
    ]);

    Payment::factory()->create([
        'team_id' => $patient->team_id,
        'patient_id' => $patient->id,
    ]);

    $this->actingAs($user)
        ->get('/portal/invoices')
        ->assertOk();
});

test('the balance shown in the portal matches the patient balance', function () {
    [$patient, $user] = portalUser();

    BillingLine::factory()->create([
        'team_id' => $patient->team_id,
        'patient_id' => $patient->id,
        'quantity' => 1,
        'unit_price' => 500,
        'discount_amount' => 0,
    ]);

    Payment::factory()->create([
        'team_id' => $patient->team_id,
        'patient_id' => $patient->id,
        'amount' => 200,
    ]);

    expect($patient->refresh()->balance)->toBe(300.0);
});

test('inviting a patient creates a portal account and sends a reset link', function () {
    Password::shouldReceive('sendResetLink')->once()->andReturn(Password::RESET_LINK_SENT);

    $admin = User::factory()->withRole(UserRole::Admin)->create();
    $patient = Patient::factory()->create([
        'team_id' => $admin->current_team_id,
        'email' => 'hasta@example.com',
    ]);

    // Simulate the portal invitation flow from ViewPatient
    $user = $patient->portalUser()->create([
        'name' => $patient->full_name,
        'email' => $patient->email,
        'password' => bcrypt(Str::random(40)),
    ]);

    $user->teams()->syncWithoutDetaching([
        $patient->team_id => ['role' => TeamRole::Member->value],
    ]);
    $user->update(['current_team_id' => $patient->team_id]);

    Password::sendResetLink(['email' => $user->email]);

    $user = $user->fresh();

    expect($patient->fresh()->hasPortalAccess())->toBeTrue()
        ->and($user->patient_id)->toBe($patient->id)
        ->and($user->isPatient())->toBeTrue()
        ->and($user->current_team_id)->toBe($patient->team_id);
});

test('a patient with a portal account is not invited twice', function () {
    $admin = User::factory()->withRole(UserRole::Admin)->create();
    $patient = Patient::factory()->create(['team_id' => $admin->current_team_id]);

    $action = new ViewPatient;

    $visible = $patient->portalUser()->doesntExist() && filled($patient->email);

    expect($visible)->toBeTrue();

    $patient->portalUser()->create([
        'name' => $patient->full_name,
        'email' => $patient->email,
        'password' => bcrypt('secret'),
    ]);

    expect($patient->fresh()->hasPortalAccess())->toBeTrue()
        ->and($patient->portalUser()->doesntExist())->toBeFalse();
});

test('the user patient relation resolves', function () {
    [$patient, $user] = portalUser();

    expect($user->patient->id)->toBe($patient->id)
        ->and($patient->portalUser->id)->toBe($user->id);
});
