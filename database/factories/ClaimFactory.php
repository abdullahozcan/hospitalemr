<?php

namespace Database\Factories;

use App\Enums\ClaimStatus;
use App\Models\Claim;
use App\Models\Patient;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Claim>
 */
class ClaimFactory extends Factory
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
            'insurer_name' => fake()->randomElement(['Anadolu Sigorta', 'AXA Sigorta', 'Allianz', 'Ziraat Sigorta', 'SGK']),
            'claim_number' => fake()->unique()->numerify('CLM-##########'),
            'amount' => fake()->randomFloat(2, 500, 50000),
            'status' => fake()->randomElement([ClaimStatus::Draft, ClaimStatus::Submitted, ClaimStatus::Approved, ClaimStatus::Denied, ClaimStatus::Paid]),
            'submitted_at' => null,
            'decided_at' => null,
            'notes' => null,
        ];
    }

    /**
     * Indicate the claim has been submitted.
     */
    public function submitted(): static
    {
        return $this->state(fn () => [
            'status' => ClaimStatus::Submitted,
            'submitted_at' => fake()->dateTimeBetween('-1 month', '-1 week'),
        ]);
    }
}
