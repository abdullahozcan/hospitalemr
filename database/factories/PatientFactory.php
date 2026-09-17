<?php

namespace Database\Factories;

use App\Enums\Gender;
use App\Models\Patient;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Patient>
 */
class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = fake()->randomElement([Gender::Male, Gender::Female]);

        return [
            'team_id' => Team::factory(),
            'first_name' => $gender === Gender::Male ? fake()->firstName('male') : fake()->firstName('female'),
            'last_name' => fake()->lastName(),
            'birth_date' => fake()->dateTimeBetween('-90 years', '-1 year'),
            'gender' => $gender,
            'national_id' => fake()->numerify('###########'),
            'blood_type' => fake()->randomElement(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', '0+', '0-']),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'district' => fake()->streetName(),
            'emergency_contact_name' => fake()->name(),
            'emergency_contact_phone' => fake()->phoneNumber(),
            'emergency_contact_relation' => fake()->randomElement(['spouse', 'parent', 'child', 'sibling']),
            'is_deceased' => false,
            'deceased_at' => null,
            'notes' => null,
        ];
    }

    /**
     * Indicate that the patient is deceased.
     */
    public function deceased(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_deceased' => true,
            'deceased_at' => fake()->dateTimeBetween('-2 years'),
        ]);
    }

    /**
     * Indicate that the patient has no contact information on file.
     */
    public function withoutContact(): static
    {
        return $this->state(fn (array $attributes) => [
            'phone' => null,
            'email' => null,
            'address' => null,
            'city' => null,
            'district' => null,
        ]);
    }
}
