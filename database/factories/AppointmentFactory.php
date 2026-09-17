<?php

namespace Database\Factories;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startsAt = Carbon::instance(fake()->dateTimeBetween('-1 week', '+2 weeks'))
            ->startOfHour()
            ->setMinutes(fake()->randomElement([0, 30]));

        return [
            'team_id' => Team::factory(),
            'patient_id' => Patient::factory(),
            'provider_id' => null,
            'facility_id' => null,
            'title' => fake()->randomElement(['Kontrol', 'İlk Muayene', 'Randevu', 'Takip']) ?? null,
            'starts_at' => $startsAt,
            'ends_at' => $startsAt->copy()->addMinutes(30),
            'status' => AppointmentStatus::Scheduled,
            'room' => fake()->randomElement([null, 'A1', 'A2', 'B1']),
            'notes' => null,
        ];
    }

    /**
     * Indicate that the appointment is today at the given hour.
     */
    public function today(int $hour = 9, int $minute = 0): static
    {
        return $this->state(fn () => [
            'starts_at' => now()->setTime($hour, $minute),
            'ends_at' => now()->setTime($hour, $minute)->addMinutes(30),
        ]);
    }

    /**
     * Indicate that the appointment is with a provider (an admin user).
     */
    public function withProvider(int|User $provider): static
    {
        return $this->state(fn () => [
            'provider_id' => is_int($provider) ? $provider : $provider->id,
        ]);
    }

    /**
     * Indicate the appointment status.
     */
    public function status(AppointmentStatus $status): static
    {
        return $this->state(fn () => [
            'status' => $status,
        ]);
    }
}
