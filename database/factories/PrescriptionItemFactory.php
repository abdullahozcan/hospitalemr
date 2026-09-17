<?php

namespace Database\Factories;

use App\Models\Drug;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PrescriptionItem>
 */
class PrescriptionItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'prescription_id' => Prescription::factory(),
            'drug_id' => Drug::factory(),
            'dosage' => '1 tablet',
            'frequency' => fake()->randomElement(['Günde 1 kez', 'Günde 2 kez', 'Günde 3 kez', 'Günde 4 kez']),
            'duration_days' => fake()->numberBetween(5, 21),
            'quantity' => fake()->numberBetween(10, 60),
            'instructions' => fake()->sentence(),
        ];
    }
}
