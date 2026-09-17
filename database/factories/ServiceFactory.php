<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
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
            'code' => fake()->unique()->bothify('SRV-####'),
            'name' => fake()->unique()->randomElement([
                'Poliklinik Muayene', 'Yatarak Tedavi', 'Kan Tetkiki', 'Röntgen',
                'MR', 'Ultrason', 'Dikiş Atma', 'EKG', 'Fizik Tedavi Seansı', 'Aşı Uygulaması',
            ]),
            'description' => fake()->sentence(),
            'price' => fake()->randomFloat(2, 100, 10000),
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the service is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn () => [
            'is_active' => false,
        ]);
    }
}
