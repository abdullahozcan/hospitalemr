<?php

namespace Database\Factories;

use App\Models\IntegrationLog;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<IntegrationLog>
 */
class IntegrationLogFactory extends Factory
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
            'service' => fake()->randomElement([IntegrationLog::SERVICE_ENABIZ, IntegrationLog::SERVICE_SGK]),
            'operation' => fake()->randomElement(['sendEncounter', 'sendPrescription', 'sendLabResults']),
            'status' => fake()->randomElement([IntegrationLog::STATUS_SUCCESS, IntegrationLog::STATUS_FAILED]),
            'code' => fake()->randomElement(['ENB-ENC-200', 'SGK-RX-200', 'ENB-PAT-400']),
            'message' => fake()->sentence(),
            'attempts' => 1,
        ];
    }
}
