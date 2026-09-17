<?php

namespace App\Models;

use App\Concerns\BelongsToTeam;
use App\Enums\LabOrderStatus;
use Database\Factories\LabOrderFactory;
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
 * @property int|null $ordered_by
 * @property LabOrderStatus $status
 * @property Carbon $ordered_at
 * @property Carbon|null $sample_collected_at
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Patient $patient
 * @property-read Encounter|null $encounter
 * @property-read User|null $orderer
 * @property-read Collection<int, LabResult> $results
 */
#[Fillable([
    'team_id',
    'patient_id',
    'encounter_id',
    'ordered_by',
    'status',
    'ordered_at',
    'sample_collected_at',
    'notes',
])]
class LabOrder extends Model
{
    use BelongsToTeam;

    /** @use HasFactory<LabOrderFactory> */
    use HasFactory, SoftDeletes;

    /**
     * The patient the tests were ordered for.
     *
     * @return BelongsTo<Patient, $this>
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * The encounter the tests were ordered in.
     *
     * @return BelongsTo<Encounter, $this>
     */
    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class);
    }

    /**
     * The doctor who ordered the tests.
     *
     * @return BelongsTo<User, $this>
     */
    public function orderer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ordered_by');
    }

    /**
     * The results recorded for this order.
     *
     * @return HasMany<LabResult, $this>
     */
    public function results(): HasMany
    {
        return $this->hasMany(LabResult::class);
    }

    /**
     * Scope to orders still waiting on the laboratory.
     *
     * @param  Builder<LabOrder>  $query
     * @return Builder<LabOrder>
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->whereIn('status', [
            LabOrderStatus::Ordered->value,
            LabOrderStatus::Collected->value,
            LabOrderStatus::InLaboratory->value,
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
            'status' => LabOrderStatus::class,
            'ordered_at' => 'datetime',
            'sample_collected_at' => 'datetime',
        ];
    }
}
