<?php

namespace Database\Factories;

use App\Enums\PrescriptionStatus;
use App\Models\Drug;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Prescription>
 */
class PrescriptionFactory extends Factory
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
            'status' => PrescriptionStatus::Active,
            'issued_at' => fake()->dateTimeBetween('-1 month'),
            'dispensed_at' => null,
            'notes' => null,
        ];
    }

    /**
     * Attach one or more items to the prescription after creation.
     */
    public function withItems(int $count = 1): static
    {
        return $this->afterCreating(function (Prescription $prescription) use ($count) {
            $drugs = Drug::factory()->count($count)->create([
                'team_id' => $prescription->team_id,
            ]);

            foreach ($drugs as $drug) {
                PrescriptionItem::create([
                    'prescription_id' => $prescription->id,
                    'drug_id' => $drug->id,
                    'dosage' => '1 tablet',
                    'frequency' => 'Günde 2 kez',
                    'duration_days' => fake()->numberBetween(5, 21),
                    'quantity' => fake()->numberBetween(10, 60),
                    'instructions' => 'Yemeklerden sonra alın.',
                ]);
            }
        });
    }
}
