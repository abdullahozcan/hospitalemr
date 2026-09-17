<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\Facility;
use App\Models\ProviderSchedule;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProviderSchedule>
 */
class ProviderScheduleFactory extends Factory
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
            'user_id' => User::factory()->withRole(UserRole::Doctor),
            'facility_id' => Facility::factory(),
            'day_of_week' => fake()->numberBetween(1, 5),
            'starts_at' => '09:00',
            'ends_at' => '17:00',
        ];
    }
}
