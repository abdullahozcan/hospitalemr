<?php

namespace Database\Factories;

use App\Models\ApiAccessLog;
use App\Models\ApiClient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ApiAccessLog>
 */
class ApiAccessLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'api_client_id' => ApiClient::factory(),
            'method' => 'GET',
            'path' => 'api/fhir/'.fake()->randomElement(['Patient', 'Encounter', 'Observation']),
            'status_code' => fake()->numberBetween(200, 500),
        ];
    }
}
