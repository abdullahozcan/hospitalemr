<?php

namespace App\Models;

use App\Concerns\BelongsToTeam;
use App\Concerns\CreatedByUser;
use Carbon\CarbonImmutable;
use Database\Factories\EncounterFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $team_id
 * @property int $patient_id
 * @property int|null $provider_id
 * @property int|null $facility_id
 * @property int|null $appointment_id
 * @property int|null $created_by
 * @property Carbon|CarbonImmutable $started_at
 * @property Carbon|CarbonImmutable|null $ended_at
 * @property string|null $chief_complaint
 * @property string|null $subjective
 * @property string|null $objective
 * @property string|null $assessment
 * @property string|null $plan
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Patient $patient
 * @property-read User|null $provider
 * @property-read Facility|null $facility
 * @property-read Appointment|null $appointment
 * @property-read Collection<int, Vital> $vitals
 * @property-read Collection<int, IcdCode> $diagnoses
 * @property-read Collection<int, Vital> $vitalsCollection
 */
#[Fillable([
    'team_id',
    'patient_id',
    'provider_id',
    'facility_id',
    'appointment_id',
    'created_by',
    'started_at',
    'ended_at',
    'chief_complaint',
    'subjective',
    'objective',
    'assessment',
    'plan',
    'notes',
])]
class Encounter extends Model
{
    use BelongsToTeam, CreatedByUser;

    /** @use HasFactory<EncounterFactory> */
    use HasFactory, SoftDeletes;

    /**
     * The patient seen during the encounter.
     *
     * @return BelongsTo<Patient, $this>
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * The provider conducting the encounter.
     *
     * @return BelongsTo<User, $this>
     */
    public function provider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    /**
     * The facility where the encounter takes place.
     *
     * @return BelongsTo<Facility, $this>
     */
    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    /**
     * The appointment this encounter was booked from.
     *
     * @return BelongsTo<Appointment, $this>
     */
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * The vital signs recorded during the encounter.
     *
     * @return HasMany<Vital, $this>
     */
    public function vitals(): HasMany
    {
        return $this->hasMany(Vital::class);
    }

    /**
     * The ICD-10 diagnoses assigned during the encounter.
     *
     * @return BelongsToMany<IcdCode, $this>
     */
    public function diagnoses(): BelongsToMany
    {
        return $this->belongsToMany(IcdCode::class, 'encounter_diagnoses')
            ->withPivot(['is_primary', 'notes'])
            ->withTimestamps();
    }

    /**
     * The billing lines charged for this encounter.
     *
     * @return HasMany<BillingLine, $this>
     */
    public function billingLines(): HasMany
    {
        return $this->hasMany(BillingLine::class);
    }

    /**
     * Scope to encounters on a given date.
     *
     * @param  Builder<Encounter>  $query
     * @return Builder<Encounter>
     */
    public function scopeOnDate(Builder $query, string|Carbon|CarbonImmutable $date): Builder
    {
        $date = Carbon::parse($date);

        return $query->whereBetween('started_at', [
            $date->copy()->startOfDay(),
            $date->copy()->endOfDay(),
        ]);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
        ];
    }
}
