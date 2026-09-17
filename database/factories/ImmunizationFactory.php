<?php

namespace Database\Factories;

use App\Models\Immunization;
use App\Models\Patient;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Immunization>
 */
class ImmunizationFactory extends Factory
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
            'vaccine_name' => fake()->randomElement(['DTaP', 'MMR', 'Influenza', 'COVID-19', 'Hepatit B', 'Polio']),
            'dose_number' => fake()->numberBetween(1, 4),
            'administered_at' => fake()->dateTimeBetween('-10 years', '-1 month'),
            'administered_by' => null,
            'notes' => null,
        ];
    }
}
