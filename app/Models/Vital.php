<?php

namespace App\Models;

use App\Concerns\BelongsToTeam;
use Database\Factories\VitalFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $team_id
 * @property int $encounter_id
 * @property int|null $measured_by
 * @property int|null $height_cm
 * @property int|null $weight_grams
 * @property int|null $systolic_mmhg
 * @property int|null $diastolic_mmhg
 * @property int|null $pulse_bpm
 * @property float|null $temperature_c
 * @property int|null $spo2_percent
 * @property int|null $respiratory_rate
 * @property Carbon|null $measured_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Encounter $encounter
 * @property-read User|null $measurer
 * @property-read float|null $bmi
 */
#[Fillable([
    'team_id',
    'encounter_id',
    'measured_by',
    'height_cm',
    'weight_grams',
    'systolic_mmhg',
    'diastolic_mmhg',
    'pulse_bpm',
    'temperature_c',
    'spo2_percent',
    'respiratory_rate',
    'measured_at',
])]
class Vital extends Model
{
    use BelongsToTeam;

    /** @use HasFactory<VitalFactory> */
    use HasFactory;

    /**
     * The encounter the vitals belong to.
     *
     * @return BelongsTo<Encounter, $this>
     */
    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class);
    }

    /**
     * The staff member who measured the vitals.
     *
     * @return BelongsTo<User, $this>
     */
    public function measurer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'measured_by');
    }

    /**
     * The body mass index, if height and weight are recorded.
     */
    public function getBmiAttribute(): ?float
    {
        if (! $this->height_cm || ! $this->weight_grams) {
            return null;
        }

        $weightKg = $this->weight_grams / 1000;
        $heightM = $this->height_cm / 100;

        return round($weightKg / ($heightM ** 2), 1);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'temperature_c' => 'float',
            'measured_at' => 'datetime',
        ];
    }
}
