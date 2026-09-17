<?php

namespace App\Models;

use App\Concerns\BelongsToTeam;
use App\Concerns\CreatedByUser;
use Database\Factories\BillingLineFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $team_id
 * @property int $patient_id
 * @property int|null $encounter_id
 * @property int $service_id
 * @property int|null $claim_id
 * @property int|null $created_by
 * @property int $quantity
 * @property float $unit_price
 * @property float $discount_amount
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Patient $patient
 * @property-read Encounter|null $encounter
 * @property-read Service $service
 * @property-read Claim|null $claim
 * @property-read float $total
 */
#[Fillable([
    'team_id',
    'patient_id',
    'encounter_id',
    'service_id',
    'claim_id',
    'created_by',
    'quantity',
    'unit_price',
    'discount_amount',
    'notes',
])]
class BillingLine extends Model
{
    use BelongsToTeam, CreatedByUser;

    /** @use HasFactory<BillingLineFactory> */
    use HasFactory, SoftDeletes;

    /**
     * The patient the line is billed to.
     *
     * @return BelongsTo<Patient, $this>
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * The encounter the service was performed in.
     *
     * @return BelongsTo<Encounter, $this>
     */
    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class);
    }

    /**
     * The billed service.
     *
     * @return BelongsTo<Service, $this>
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * The insurance claim this line was assigned to.
     *
     * @return BelongsTo<Claim, $this>
     */
    public function claim(): BelongsTo
    {
        return $this->belongsTo(Claim::class);
    }

    /**
     * The line total including discounts.
     */
    public function getTotalAttribute(): float
    {
        return round($this->quantity * $this->unit_price - $this->discount_amount, 2);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'unit_price' => 'decimal:2',
            'discount_amount' => 'decimal:2',
        ];
    }
}
