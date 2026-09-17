<?php

namespace App\Models;

use App\Concerns\BelongsToTeam;
use App\Enums\PaymentMethod;
use Database\Factories\PaymentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $team_id
 * @property int $patient_id
 * @property int|null $received_by
 * @property PaymentMethod $method
 * @property float $amount
 * @property Carbon $paid_at
 * @property string|null $reference
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Patient $patient
 * @property-read User|null $receiver
 */
#[Fillable([
    'team_id',
    'patient_id',
    'received_by',
    'method',
    'amount',
    'paid_at',
    'reference',
    'notes',
])]
class Payment extends Model
{
    use BelongsToTeam;

    /** @use HasFactory<PaymentFactory> */
    use HasFactory, SoftDeletes;

    /**
     * The patient who made the payment.
     *
     * @return BelongsTo<Patient, $this>
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * The staff member who collected the payment.
     *
     * @return BelongsTo<User, $this>
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    /**
     * Scope to payments made on a given date.
     *
     * @param  Builder<Payment>  $query
     * @return Builder<Payment>
     */
    public function scopeOnDate(Builder $query, string|Carbon $date): Builder
    {
        return $query->whereBetween('paid_at', [
            Carbon::parse($date)->startOfDay(),
            Carbon::parse($date)->endOfDay(),
        ]);
    }

    /**
     * Scope to insurance payments.
     *
     * @param  Builder<Payment>  $query
     * @return Builder<Payment>
     */
    public function scopeInsurance(Builder $query): Builder
    {
        return $query->where('method', PaymentMethod::Insurance->value);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'method' => PaymentMethod::class,
            'amount' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }
}
