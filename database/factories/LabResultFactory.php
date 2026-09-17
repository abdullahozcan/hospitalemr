<?php

namespace Database\Factories;

use App\Enums\LabResultFlag;
use App\Models\LabOrder;
use App\Models\LabResult;
use App\Models\LabTest;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LabResult>
 */
class LabResultFactory extends Factory
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
            'lab_order_id' => LabOrder::factory(),
            'lab_test_id' => LabTest::factory(),
            'value_numeric' => fake()->randomFloat(2, 1, 200),
            'flag' => fake()->randomElement([LabResultFlag::Normal, LabResultFlag::Normal, LabResultFlag::High, LabResultFlag::Low]),
            'resulted_at' => fake()->dateTimeBetween('-1 week', '-1 hour'),
            'notes' => null,
        ];
    }

    /**
     * Indicate the result has been reviewed by a doctor.
     */
    public function reviewed(): static
    {
        return $this->state(fn () => [
            'reviewed_at' => fake()->dateTimeBetween('-1 hour'),
        ]);
    }
}
