<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\PatientDocument;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

/**
 * @extends Factory<PatientDocument>
 */
class PatientDocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $path = 'patient-documents/'.fake()->uuid().'.pdf';

        Storage::disk('public')->put($path, 'test document content');

        return [
            'patient_id' => Patient::factory(),
            'title' => fake()->randomElement(['Laboratuvar Raporu', 'Röntgen Görüntüsü', 'Kan Testi', 'İşe Giriş Raporu', 'Ameliyat Raporu']),
            'file_path' => $path,
        ];
    }
}
