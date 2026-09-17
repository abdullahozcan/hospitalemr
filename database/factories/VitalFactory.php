<?php

namespace Database\Factories;

use App\Models\Encounter;
use App\Models\Team;
use App\Models\Vital;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Vital>
 */
class VitalFactory extends Factory
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
            'encounter_id' => Encounter::factory(),
            'height_cm' => fake()->numberBetween(60, 200),
            'weight_grams' => fake()->numberBetween(4000, 120000),
            'systolic_mmhg' => fake()->numberBetween(100, 160),
            'diastolic_mmhg' => fake()->numberBetween(60, 100),
            'pulse_bpm' => fake()->numberBetween(55, 110),
            'temperature_c' => fake()->randomFloat(1, 35.5, 39.5),
            'spo2_percent' => fake()->numberBetween(90, 100),
            'respiratory_rate' => fake()->numberBetween(12, 24),
            'measured_at' => now(),
        ];
    }
}
