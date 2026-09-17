<?php

namespace Database\Factories;

use App\Enums\PaymentMethod;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
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
            'method' => fake()->randomElement([PaymentMethod::Cash, PaymentMethod::Card, PaymentMethod::Transfer, PaymentMethod::Insurance]),
            'amount' => fake()->randomFloat(2, 100, 10000),
            'paid_at' => fake()->dateTimeBetween('-1 month'),
            'reference' => fake()->optional()->bothify('REF-######'),
            'notes' => null,
        ];
    }

    /**
     * Indicate the payment was made in cash today.
     */
    public function cashToday(): static
    {
        return $this->state(fn () => [
            'method' => PaymentMethod::Cash,
            'paid_at' => now(),
        ]);
    }
}
