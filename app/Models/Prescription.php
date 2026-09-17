<?php

namespace App\Models;

use App\Concerns\BelongsToTeam;
use App\Concerns\CreatedByUser;
use App\Enums\PrescriptionStatus;
use Database\Factories\PrescriptionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $team_id
 * @property int $patient_id
 * @property int|null $encounter_id
 * @property int|null $prescriber_id
 * @property int|null $created_by
 * @property PrescriptionStatus $status
 * @property Carbon $issued_at
 * @property Carbon|null $dispensed_at
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Patient $patient
 * @property-read User|null $prescriber
 * @property-read Encounter|null $encounter
 * @property-read Collection<int, PrescriptionItem> $items
 */
#[Fillable([
    'team_id',
    'patient_id',
    'encounter_id',
    'prescriber_id',
    'created_by',
    'status',
    'issued_at',
    'dispensed_at',
    'notes',
])]
class Prescription extends Model
{
    use BelongsToTeam, CreatedByUser;

    /** @use HasFactory<PrescriptionFactory> */
    use HasFactory, SoftDeletes;

    /**
     * The patient the prescription is for.
     *
     * @return BelongsTo<Patient, $this>
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * The encounter the prescription belongs to.
     *
     * @return BelongsTo<Encounter, $this>
     */
    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class);
    }

    /**
     * The doctor who prescribed the medication.
     *
     * @return BelongsTo<User, $this>
     */
    public function prescriber(): BelongsTo
    {
        return $this->belongsTo(User::class, 'prescriber_id');
    }

    /**
     * The medication lines on this prescription.
     *
     * @return HasMany<PrescriptionItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(PrescriptionItem::class);
    }

    /**
     * Scope to prescriptions issued on a given date.
     *
     * @param  Builder<Prescription>  $query
     * @return Builder<Prescription>
     */
    public function scopeOnDate(Builder $query, string|Carbon $date): Builder
    {
        return $query->whereBetween('issued_at', [
            Carbon::parse($date)->startOfDay(),
            Carbon::parse($date)->endOfDay(),
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
            'status' => PrescriptionStatus::class,
            'issued_at' => 'datetime',
            'dispensed_at' => 'datetime',
        ];
    }
}
