<?php

namespace Database\Factories;

use App\Models\BillingLine;
use App\Models\Patient;
use App\Models\Service;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BillingLine>
 */
class BillingLineFactory extends Factory
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
            'quantity' => fake()->numberBetween(1, 3),
            'unit_price' => fake()->randomFloat(2, 100, 5000),
            'discount_amount' => 0,
            'notes' => null,
        ];
    }

    /**
     * Configure the factory to use a service from the same team.
     */
    public function configure(): static
    {
        return $this->afterMaking(function (BillingLine $billingLine) {
            if (! $billingLine->service_id) {
                $billingLine->service_id = Service::factory()->create([
                    'team_id' => $billingLine->team_id,
                ])->id;
            }
        });
    }

    /**
     * Indicate the line has a discount.
     */
    public function discounted(float $amount = 100): static
    {
        return $this->state(fn () => [
            'discount_amount' => $amount,
        ]);
    }
}
