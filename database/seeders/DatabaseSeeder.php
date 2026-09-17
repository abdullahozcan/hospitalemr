<?php

namespace Database\Seeders;

use App\Enums\LabResultFlag;
use App\Enums\UserRole;
use App\Models\Appointment;
use App\Models\BillingLine;
use App\Models\Drug;
use App\Models\Encounter;
use App\Models\Facility;
use App\Models\LabOrder;
use App\Models\LabResult;
use App\Models\LabTest;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\ProviderSchedule;
use App\Models\Service;
use App\Models\User;
use App\Models\Vital;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            IcdCodeSeeder::class,
        ]);

        $admin = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole(UserRole::Admin);

        $doctor = User::factory()->create([
            'name' => 'Dr. Ali Hekim',
            'email' => 'doctor@example.com',
            'password' => bcrypt('password'),
        ]);
        $doctor->assignRole(UserRole::Doctor);

        $facility = Facility::factory()->create([
            'team_id' => $admin->current_team_id,
        ]);

        foreach (range(1, 5) as $day) {
            ProviderSchedule::create([
                'team_id' => $admin->current_team_id,
                'user_id' => $doctor->id,
                'facility_id' => $facility->id,
                'day_of_week' => $day,
                'starts_at' => '09:00',
                'ends_at' => '17:00',
            ]);
        }

        $patients = Patient::factory()->count(10)->create([
            'team_id' => $admin->current_team_id,
        ]);

        $appointments = Appointment::factory()->count(3)->today()->sequence(
            ['starts_at' => now()->today()->setTime(9, 0)],
            ['starts_at' => now()->today()->setTime(10, 0)],
            ['starts_at' => now()->today()->setTime(11, 30)],
        )
            ->create([
                'team_id' => $admin->current_team_id,
                'provider_id' => $doctor->id,
                'facility_id' => $facility->id,
                'patient_id' => $patients->random()->id,
            ]);

        $encounter = Encounter::factory()->create([
            'team_id' => $admin->current_team_id,
            'patient_id' => $appointments->first()->patient_id,
            'provider_id' => $doctor->id,
            'facility_id' => $facility->id,
            'appointment_id' => $appointments->first()->id,
        ]);

        Vital::factory()->create([
            'team_id' => $admin->current_team_id,
            'encounter_id' => $encounter->id,
        ]);

        $drug = Drug::factory()->create([
            'team_id' => $admin->current_team_id,
            'name' => 'Parol',
            'active_ingredient' => 'Parasetamol',
            'form' => 'tablet',
            'strength' => '500 mg',
            'stock_quantity' => 150,
        ]);

        $prescription = Prescription::factory()->create([
            'team_id' => $admin->current_team_id,
            'patient_id' => $encounter->patient_id,
            'encounter_id' => $encounter->id,
            'prescriber_id' => $doctor->id,
        ]);

        PrescriptionItem::create([
            'prescription_id' => $prescription->id,
            'drug_id' => $drug->id,
            'dosage' => '1 tablet',
            'frequency' => 'Günde 3 kez',
            'duration_days' => 7,
            'quantity' => 21,
            'instructions' => 'Yemeklerden sonra bol su ile alın.',
        ]);

        $consultation = Service::create([
            'team_id' => $admin->current_team_id,
            'code' => 'SRV-1001',
            'name' => 'Poliklinik Muayene',
            'price' => 750,
        ]);

        $labService = Service::create([
            'team_id' => $admin->current_team_id,
            'code' => 'SRV-1002',
            'name' => 'Kan Tetkiki',
            'price' => 450,
        ]);

        BillingLine::create([
            'team_id' => $admin->current_team_id,
            'patient_id' => $encounter->patient_id,
            'encounter_id' => $encounter->id,
            'service_id' => $consultation->id,
            'quantity' => 1,
            'unit_price' => $consultation->price,
        ]);

        BillingLine::create([
            'team_id' => $admin->current_team_id,
            'patient_id' => $encounter->patient_id,
            'encounter_id' => $encounter->id,
            'service_id' => $labService->id,
            'quantity' => 1,
            'unit_price' => $labService->price,
        ]);

        $glucoseTest = LabTest::factory()->create([
            'team_id' => $admin->current_team_id,
            'name' => 'Glukoz',
            'category' => 'Biyokimya',
            'unit' => 'mg/dL',
        ]);

        $labOrder = LabOrder::factory()->create(['team_id' => $admin->current_team_id,
            'patient_id' => $encounter->patient_id,
            'encounter_id' => $encounter->id,
            'ordered_by' => $doctor->id,
        ]);

        LabResult::create([
            'team_id' => $admin->current_team_id,
            'lab_order_id' => $labOrder->id,
            'lab_test_id' => $glucoseTest->id,
            'value_numeric' => 92,
            'flag' => LabResultFlag::Normal,
            'resulted_at' => now(),
        ]);
    }
}
