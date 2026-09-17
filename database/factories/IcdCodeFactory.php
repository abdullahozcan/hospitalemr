<?php

namespace Database\Factories;

use App\Models\IcdCode;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<IcdCode>
 */
class IcdCodeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $code = fake()->unique()->bothify('?##.##');

        return [
            'code' => str_starts_with($code, '.') ? 'A'.$code : $code,
            'description' => fake()->sentence(4),
        ];
    }
}
