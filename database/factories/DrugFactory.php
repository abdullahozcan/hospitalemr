<?php

namespace Database\Factories;

use App\Models\Drug;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Drug>
 */
class DrugFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Parol', 'Ventolin', 'Ampicillin', 'Nexium', 'Plavix', 'Glucofage',
            'Zestril', 'Concor', 'Zyrtec', 'Augmentin', 'Nurofen', 'Dolorex',
        ]);

        return [
            'team_id' => Team::factory(),
            'code' => 'DRG-'.fake()->unique()->numerify('#####'),
            'name' => $name,
            'active_ingredient' => fake()->randomElement(['Parasetamol', 'Salbutamol', 'Amoksisilin', 'Esomeprazol', 'Metformin']),
            'form' => fake()->randomElement(['tablet', 'kapsül', 'şurup', 'enjektör', 'sprey']),
            'strength' => fake()->randomElement(['500 mg', '10 mg', '250 mg', '1000 mg']),
            'unit' => 'mg',
            'is_prescription_only' => fake()->boolean(70),
            'stock_quantity' => fake()->numberBetween(0, 500),
            'low_stock_threshold' => 20,
            'notes' => null,
        ];
    }

    /**
     * Indicate that the drug is out of or low on stock.
     */
    public function lowStock(): static
    {
        return $this->state(fn () => [
            'stock_quantity' => 5,
            'low_stock_threshold' => 20,
        ]);
    }
}
