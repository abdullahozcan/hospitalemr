<?php

namespace Database\Factories;

use App\Models\InsurancePolicy;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<InsurancePolicy>
 */
class InsurancePolicyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = Carbon::instance(fake()->dateTimeBetween('-3 years', '-1 month'));

        return [
            'company_name' => fake()->randomElement(['Anadolu Sigorta', 'AXA Sigorta', 'Allianz', 'Ziraat Sigorta', 'Doğuş Sigorta']),
            'policy_number' => fake()->unique()->numerify('POL-##########'),
            'start_date' => $start,
            'end_date' => $start->copy()->addYear(),
            'notes' => null,
        ];
    }

    /**
     * Indicate that the policy has expired.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'start_date' => fake()->dateTimeBetween('-3 years', '-2 years'),
            'end_date' => fake()->dateTimeBetween('-2 years', '-1 year'),
        ]);
    }
}
