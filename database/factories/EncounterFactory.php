<?php

namespace Database\Factories;

use App\Models\Encounter;
use App\Models\Facility;
use App\Models\Patient;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Encounter>
 */
class EncounterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'patient_id' => Patient::factory(),
            'provider_id' => null,
            'facility_id' => Facility::factory(),
            'started_at' => fake()->dateTimeBetween('-1 month'),
            'ended_at' => null,
            'chief_complaint' => fake()->randomElement(['Baş ağrısı', 'Öksürük', 'Karın ağrısı', 'Yaralanma', 'Kontrol muayenesi']),
            'subjective' => fake()->sentence(),
            'objective' => fake()->sentence(),
            'assessment' => fake()->sentence(),
            'plan' => fake()->sentence(),
            'notes' => null,
        ];
    }

    /**
     * Indicate that the encounter is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'ended_at' => Carbon::parse($attributes['started_at'])->addMinutes(30),
        ]);
    }
}
