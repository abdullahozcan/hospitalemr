<?php

namespace Database\Factories;

use App\Enums\AllergySeverity;
use App\Models\Allergy;
use App\Models\Patient;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Allergy>
 */
class AllergyFactory extends Factory
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
            'allergen' => fake()->randomElement(['Penisilin', 'Yer fıstığı', 'Arı sokması', 'Polen', 'Sülfür ilaçlar', 'Lateks']),
            'reaction' => fake()->randomElement(['Döküntü', 'Kaşıntı', 'Nefes darlığı', 'Şişme', null]),
            'severity' => fake()->randomElement([AllergySeverity::Mild, AllergySeverity::Moderate, AllergySeverity::Severe]),
            'noted_at' => fake()->dateTimeBetween('-5 years'),
            'notes' => null,
        ];
    }
}
