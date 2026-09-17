<?php

use App\Enums\AppointmentStatus;
use App\Enums\TeamRole;
use App\Enums\UserRole;
use App\Filament\Pages\FlowBoard;
use App\Filament\Resources\Appointments\Pages\CreateAppointment;
use App\Filament\Resources\Appointments\Pages\EditAppointment;
use App\Filament\Resources\Appointments\Pages\ListAppointments;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\ProviderSchedule;
use App\Models\Team;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Support\Facades\Gate;
use Livewire\Livewire;

beforeEach(fn () => $this->seed(RolePermissionSeeder::class));

test('appointment queries are scoped to the current team', function () {
    $teamA = Team::factory()->create();
    $teamB = Team::factory()->create();

    $appointmentA = Appointment::factory()->create(['team_id' => $teamA->id]);
    Appointment::factory()->create(['team_id' => $teamB->id]);

    $user = User::factory()->create();
    $teamA->members()->attach($user, ['role' => TeamRole::Member->value]);
    $user->switchTeam($teamA);
    $this->actingAs($user);

    expect(Appointment::all()->pluck('id')->all())->toBe([$appointmentA->id]);
});

test('providers cannot have overlapping appointments', function () {
    $provider = User::factory()->withRole(UserRole::Doctor)->create();
    $start = now()->startOfDay()->addDay()->addHours(10);

    Appointment::factory()->create([
        'provider_id' => $provider->id,
        'starts_at' => $start,
        'ends_at' => $start->copy()->addMinutes(30),
    ]);

    expect(Appointment::providerHasOverlap(
        $provider->id,
        $start->copy()->addMinutes(15),
        $start->copy()->addMinutes(45),
    ))->toBeTrue()
        ->and(Appointment::providerHasOverlap(
            $provider->id,
            $start->copy()->addMinutes(30),
            $start->copy()->addMinutes(60),
        ))->toBeFalse();
});

test('cancelled appointments do not block the schedule', function () {
    $provider = User::factory()->withRole(UserRole::Doctor)->create();
    $start = now()->startOfDay()->addDay()->addHours(10);

    Appointment::factory()->create([
        'provider_id' => $provider->id,
        'starts_at' => $start,
        'ends_at' => $start->copy()->addMinutes(30),
        'status' => AppointmentStatus::Cancelled,
    ]);

    expect(Appointment::providerHasOverlap(
        $provider->id,
        $start,
        $start->copy()->addMinutes(30),
    ))->toBeFalse();
});

test('the creation form prevents overlapping appointments', function () {
    $doctor = User::factory()->withRole(UserRole::Doctor)->create();
    $receptionist = User::factory()->withRole(UserRole::Receptionist)->create();
    $this->actingAs($receptionist);

    $start = now()->startOfDay()->addDay()->addHours(10);

    Appointment::factory()->create([
        'team_id' => $receptionist->current_team_id,
        'provider_id' => $doctor->id,
        'starts_at' => $start,
        'ends_at' => $start->copy()->addMinutes(30),
    ]);

    $patient = Patient::factory()->create(['team_id' => $receptionist->current_team_id]);

    Livewire::test(CreateAppointment::class)
        ->fillForm([
            'patient_id' => $patient->id,
            'provider_id' => $doctor->id,
            'starts_at' => $start->copy()->addMinutes(10)->format('Y-m-d H:i:s'),
            'ends_at' => $start->copy()->addMinutes(40)->format('Y-m-d H:i:s'),
        ])
        ->call('create')
        ->assertHasFormErrors(['starts_at']);
});

test('receptionists can create non-overlapping appointments', function () {
    $receptionist = User::factory()->withRole(UserRole::Receptionist)->create();
    $this->actingAs($receptionist);

    $patient = Patient::factory()->create(['team_id' => $receptionist->current_team_id]);
    $start = now()->startOfDay()->addDay()->addHours(14);

    Livewire::test(CreateAppointment::class)
        ->fillForm([
            'patient_id' => $patient->id,
            'provider_id' => null,
            'starts_at' => $start->format('Y-m-d H:i:s'),
            'ends_at' => $start->copy()->addMinutes(30)->format('Y-m-d H:i:s'),
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Appointment::query()->where('patient_id', $patient->id)->first())->not->toBeNull();
});

test('accountants cannot create appointments', function () {
    $user = User::factory()->withRole(UserRole::Accountant)->create();

    expect($user->can('create', Appointment::class))->toBeFalse();
});

test('doctors can update appointments through the panel', function () {
    $doctor = User::factory()->withRole(UserRole::Doctor)->create();
    $patient = Patient::factory()->create(['team_id' => $doctor->current_team_id]);
    $appointment = Appointment::factory()->create([
        'team_id' => $doctor->current_team_id,
        'patient_id' => $patient->id,
    ]);

    $this->actingAs($doctor);

    Livewire::test(EditAppointment::class, ['record' => $appointment->getKey()])
        ->fillForm(['notes' => 'Hasta kan grubu hakkında bilgilendirildi.'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($appointment->refresh()->notes)->toBe('Hasta kan grubu hakkında bilgilendirildi.');
});

test('appointments transition through statuses', function () {
    $appointment = Appointment::factory()->create(['status' => AppointmentStatus::Scheduled]);

    expect($appointment->status->nextStatuses())->toContain(AppointmentStatus::CheckedIn)
        ->and(AppointmentStatus::Completed->nextStatuses())->toBe([]);
});

test('doctors can mark appointments as completed from the panel', function () {
    $doctor = User::factory()->withRole(UserRole::Doctor)->create();
    $patient = Patient::factory()->create(['team_id' => $doctor->current_team_id]);
    $appointment = Appointment::factory()->create([
        'team_id' => $doctor->current_team_id,
        'patient_id' => $patient->id,
        'status' => AppointmentStatus::InProgress,
    ]);

    $this->actingAs($doctor);

    Livewire::test(ListAppointments::class)
        ->callTableAction('mark_completed', $appointment);

    expect($appointment->refresh()->status)->toBe(AppointmentStatus::Completed);
});

test('the flow board shows today\'s waiting appointments', function () {
    $user = User::factory()->withRole(UserRole::Receptionist)->create();
    $this->actingAs($user);

    $waiting = Appointment::factory()->create([
        'team_id' => $user->current_team_id,
        'starts_at' => now()->today()->setTime(9, 0),
        'status' => AppointmentStatus::Scheduled,
    ]);
    Appointment::factory()->create([
        'team_id' => $user->current_team_id,
        'starts_at' => now()->subDays(2)->setTime(9, 0),
        'status' => AppointmentStatus::Scheduled,
    ]);
    Appointment::factory()->create([
        'team_id' => $user->current_team_id,
        'starts_at' => now()->today()->setTime(10, 0),
        'status' => AppointmentStatus::Completed,
    ]);

    Livewire::test(FlowBoard::class)->assertCanSeeTableRecords([$waiting]);
});

test('the flow board is not accessible without the appointment view permission', function () {
    $user = User::factory()->withRole(UserRole::Accountant)->create();
    $this->actingAs($user);

    expect(Gate::allows('viewAny', Appointment::class))->toBeFalse()
        ->and(FlowBoard::canAccess())->toBeFalse();
});

test('provider schedules can be created for weekdays', function () {
    $schedule = ProviderSchedule::factory()->create();

    expect($schedule->provider->hasRole(UserRole::Doctor->value))->toBeTrue()
        ->and($schedule->day_of_week)->toBeInt();
});
