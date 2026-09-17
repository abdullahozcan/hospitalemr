<?php

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\ProviderSchedule;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;

beforeEach(fn () => $this->seed(RolePermissionSeeder::class));

test('the home page lists doctors with booking actions', function () {
    $user = \App\Models\User::factory()->withRole(\App\Enums\UserRole::Doctor)->create();

    $this->get('/')->assertOk()->assertSee('Dr. '.$user->name);
});

test('the doctor search finds doctors by name', function () {
    $doctor = \App\Models\User::factory()->withRole(\App\Enums\UserRole::Doctor)->create(['name' => 'Zeynep Kaya']);

    $this->get('/doktorlar?q=Zeynep')->assertOk()->assertSee('Zeynep Kaya');

    $this->get('/doktorlar?q=Nonexistent')->assertOk()->assertSee(__('public.doctors.no_results'));
});

test('the booking page lists available slots for a scheduled day', function () {
    $doctor = \App\Models\User::factory()->withRole(\App\Enums\UserRole::Doctor)->create();

    // find next weekday from tomorrow
    $date = now()->addDay()->startOfDay();
    while ($date->dayOfWeekIso > 5) {
        $date = $date->addDay();
    }

    ProviderSchedule::create([
        'team_id' => $doctor->current_team_id,
        'user_id' => $doctor->id,
        'day_of_week' => $date->dayOfWeekIso,
        'starts_at' => '09:00',
        'ends_at' => '12:00',
    ]);

    $response = $this->get('/doktorlar/'.$doctor->id.'/randevu?date='.$date->format('Y-m-d'));

    $response->assertOk();
});

test('booking a slot requires authentication', function () {
    $doctor = \App\Models\User::factory()->withRole(\App\Enums\UserRole::Doctor)->create();

    $this->post('/doktorlar/'.$doctor->id.'/randevu', [
        'starts_at' => now()->addDay()->setTime(10, 0),
        'ends_at' => now()->addDay()->setTime(10, 30),
    ])->assertRedirect('/login');
});

test('patients can book an appointment from a public slot', function () {
    $admin = \App\Models\User::factory()->withRole(\App\Enums\UserRole::Admin)->create();
    $doctor = \App\Models\User::factory()->withRole(\App\Enums\UserRole::Doctor)->create();
    $patientRecord = Patient::factory()->create(['team_id' => $admin->current_team_id]);
    $patient = \App\Models\User::factory()->create(['patient_id' => $patientRecord->id]);
    $patient->teams()->syncWithoutDetaching([
        $admin->current_team_id => ['role' => \App\Enums\TeamRole::Member->value],
    ]);
    $patient->update(['current_team_id' => $admin->current_team_id]);

    ProviderSchedule::create([
        'team_id' => $admin->current_team_id,
        'user_id' => $doctor->id,
        'day_of_week' => now()->addDay()->dayOfWeekIso,
        'starts_at' => '09:00',
        'ends_at' => '17:00',
    ]);

    $startsAt = now()->addDay()->setTime(10, 0)->format('Y-m-d H:i:s');

    $this->actingAs($patient)
        ->post('/doktorlar/'.$doctor->id.'/randevu', [
            'starts_at' => $startsAt,
            'ends_at' => now()->addDay()->setTime(10, 30)->format('Y-m-d H:i:s'),
        ])
        ->assertRedirect();

    $appointment = Appointment::query()->where('patient_id', $patientRecord->id)->first();

    expect($appointment)->not->toBeNull()
        ->and($appointment->provider_id)->toBe($doctor->id)
        ->and($appointment->status->value)->toBe('scheduled');
});

test('clinic users without a patient record cannot book', function () {
    $receptionist = \App\Models\User::factory()->withRole(\App\Enums\UserRole::Receptionist)->create();
    $doctor = \App\Models\User::factory()->withRole(\App\Enums\UserRole::Doctor)->create();

    $this->actingAs($receptionist)
        ->post('/doktorlar/'.$doctor->id.'/randevu', [
            'starts_at' => now()->addDay()->setTime(10, 0),
            'ends_at' => now()->addDay()->setTime(10, 30),
        ])
        ->assertSessionHas('error');
});

test('overlapping slots are rejected', function () {
    $admin = \App\Models\User::factory()->withRole(\App\Enums\UserRole::Admin)->create();
    $doctor = \App\Models\User::factory()->withRole(\App\Enums\UserRole::Doctor)->create();
    $patientRecord = Patient::factory()->create(['team_id' => $admin->current_team_id]);
    $patient = \App\Models\User::factory()->create(['patient_id' => $patientRecord->id]);
    $patient->teams()->syncWithoutDetaching([
        $admin->current_team_id => ['role' => \App\Enums\TeamRole::Member->value],
    ]);
    $patient->update(['current_team_id' => $admin->current_team_id]);

    Appointment::factory()->create([
        'team_id' => $admin->current_team_id,
        'provider_id' => $doctor->id,
        'starts_at' => now()->addDay()->setTime(10, 0),
        'ends_at' => now()->addDay()->setTime(10, 30),
    ]);

    $this->actingAs($patient)
        ->post('/doktorlar/'.$doctor->id.'/randevu', [
            'starts_at' => now()->addDay()->setTime(10, 10)->format('Y-m-d H:i:s'),
            'ends_at' => now()->addDay()->setTime(10, 40)->format('Y-m-d H:i:s'),
        ])
        ->assertSessionHasErrors('starts_at');
});

test('the slot service generates schedule windows', function () {
    $doctor = \App\Models\User::factory()->withRole(\App\Enums\UserRole::Doctor)->create();

    $date = now()->addDay()->startOfDay();
    while ($date->dayOfWeekIso > 5) {
        $date = $date->addDay();
    }

    ProviderSchedule::create([
        'team_id' => $doctor->current_team_id,
        'user_id' => $doctor->id,
        'day_of_week' => $date->dayOfWeekIso,
        'starts_at' => '09:00',
        'ends_at' => '11:00',
    ]);

    $slots = app(\App\Services\SlotAvailabilityService::class)->forDoctor($doctor, $date);

    expect($slots->count())->toBe(4)
        ->and($slots->first()['label'])->toBe('09:00')
        ->and($slots->last()['label'])->toBe('10:30');
});
