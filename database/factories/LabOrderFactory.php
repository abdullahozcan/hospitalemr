<?php

namespace Database\Factories;

use App\Enums\LabOrderStatus;
use App\Models\LabOrder;
use App\Models\Patient;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LabOrder>
 */
class LabOrderFactory extends Factory
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
            'status' => LabOrderStatus::Ordered,
            'ordered_at' => fake()->dateTimeBetween('-1 week'),
            'sample_collected_at' => null,
            'notes' => null,
        ];
    }

    /**
     * Indicate the order status.
     */
    public function status(LabOrderStatus $status): static
    {
        return $this->state(fn () => [
            'status' => $status,
            'sample_collected_at' => $status === LabOrderStatus::Ordered ? null : now(),
        ]);
    }
}
