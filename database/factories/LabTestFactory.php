<?php

namespace Database\Factories;

use App\Models\LabTest;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LabTest>
 */
class LabTestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Hemogram (Tam Kan Sayımı)', 'Glukoz', 'HbA1c', 'Kolesterol (Total)',
            'TSH', 'Karaciğer Enzimleri (ALT)', 'Böbrek Fonksiyonu (Kreatinin)', 'Sodyum', 'Potasyum',
        ]);

        return [
            'team_id' => Team::factory(),
            'code' => 'LAB-'.fake()->unique()->numerify('####'),
            'name' => $name,
            'category' => fake()->randomElement(['Hematoloji', 'Biyokimya', 'Hormon', 'Mikrobiyoloji']),
            'sample_type' => fake()->randomElement(['Kan', 'İdrar', 'Sürüntü']),
            'unit' => fake()->randomElement(['mg/dL', 'g/dL', 'mIU/L', 'U/L']),
            'reference_min' => null,
            'reference_max' => null,
            'is_active' => true,
        ];
    }

    /**
     * Indicate the test has reference ranges.
     */
    public function withReferenceRange(float $min, float $max): static
    {
        return $this->state(fn () => [
            'reference_min' => $min,
            'reference_max' => $max,
        ]);
    }

    /**
     * Indicate that the test is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn () => [
            'is_active' => false,
        ]);
    }
}
