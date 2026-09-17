<?php

namespace App\Models;

use App\Concerns\BelongsToTeam;
use App\Enums\ClaimStatus;
use Database\Factories\ClaimFactory;
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
 * @property string $insurer_name
 * @property string|null $claim_number
 * @property float $amount
 * @property ClaimStatus $status
 * @property Carbon|null $submitted_at
 * @property Carbon|null $decided_at
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Patient $patient
 * @property-read Collection<int, BillingLine> $billingLines
 */
#[Fillable([
    'team_id',
    'patient_id',
    'insurer_name',
    'claim_number',
    'amount',
    'status',
    'submitted_at',
    'decided_at',
    'notes',
])]
class Claim extends Model
{
    use BelongsToTeam;

    /** @use HasFactory<ClaimFactory> */
    use HasFactory, SoftDeletes;

    /**
     * The patient the claim is for.
     *
     * @return BelongsTo<Patient, $this>
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * The billing lines assigned to this claim.
     *
     * @return HasMany<BillingLine, $this>
     */
    public function billingLines(): HasMany
    {
        return $this->hasMany(BillingLine::class);
    }

    /**
     * Scope to claims that are still in flight.
     *
     * @param  Builder<Claim>  $query
     * @return Builder<Claim>
     */
    public function scopeOpen(Builder $query): Builder
    {
        return $query->whereIn('status', [ClaimStatus::Draft->value, ClaimStatus::Submitted->value]);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => ClaimStatus::class,
            'amount' => 'decimal:2',
            'submitted_at' => 'datetime',
            'decided_at' => 'datetime',
        ];
    }
}
