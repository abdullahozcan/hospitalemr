<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('icd_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 8)->unique();
            $table->string('description');
            $table->timestamps();
        });

        Schema::create('encounters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('provider_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('facility_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('appointment_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();
            $table->string('chief_complaint')->nullable();
            $table->text('subjective')->nullable();
            $table->text('objective')->nullable();
            $table->text('assessment')->nullable();
            $table->text('plan')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('started_at');
        });

        Schema::create('vitals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->foreignId('encounter_id')->constrained()->cascadeOnDelete();
            $table->foreignId('measured_by')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedTinyInteger('height_cm')->nullable();
            $table->unsignedInteger('weight_grams')->nullable();
            $table->unsignedTinyInteger('systolic_mmhg')->nullable();
            $table->unsignedTinyInteger('diastolic_mmhg')->nullable();
            $table->unsignedTinyInteger('pulse_bpm')->nullable();
            $table->decimal('temperature_c', 4, 1)->nullable();
            $table->unsignedTinyInteger('spo2_percent')->nullable();
            $table->unsignedTinyInteger('respiratory_rate')->nullable();
            $table->timestamp('measured_at')->nullable();
            $table->timestamps();

            $table->index('encounter_id');
        });

        Schema::create('encounter_diagnoses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('encounter_id')->constrained()->cascadeOnDelete();
            $table->foreignId('icd_code_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_primary')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['encounter_id', 'icd_code_id']);
        });

        Schema::create('allergies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->string('allergen');
            $table->string('reaction')->nullable();
            $table->string('severity', 16)->nullable();
            $table->timestamp('noted_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('immunizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->string('vaccine_name');
            $table->unsignedTinyInteger('dose_number')->nullable();
            $table->timestamp('administered_at');
            $table->foreignId('administered_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('immunizations');
        Schema::dropIfExists('allergies');
        Schema::dropIfExists('encounter_diagnoses');
        Schema::dropIfExists('vitals');
        Schema::dropIfExists('encounters');
        Schema::dropIfExists('icd_codes');
    }
};
