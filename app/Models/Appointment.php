<?php

namespace App\Models;

use App\Concerns\BelongsToTeam;
use App\Concerns\CreatedByUser;
use App\Enums\AppointmentStatus;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Database\Factories\AppointmentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $team_id
 * @property int $patient_id
 * @property int|null $provider_id
 * @property int|null $facility_id
 * @property int|null $created_by
 * @property string|null $title
 * @property Carbon $starts_at
 * @property Carbon $ends_at
 * @property AppointmentStatus $status
 * @property string|null $room
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Patient $patient
 * @property-read User|null $provider
 * @property-read Facility|null $facility
 * @property-read int $duration_minutes
 */
#[Fillable([
    'team_id',
    'patient_id',
    'provider_id',
    'facility_id',
    'created_by',
    'title',
    'starts_at',
    'ends_at',
    'status',
    'room',
    'notes',
])]
class Appointment extends Model
{
    use BelongsToTeam, CreatedByUser;

    /** @use HasFactory<AppointmentFactory> */
    use HasFactory, SoftDeletes;

    /**
     * The patient the appointment is for.
     *
     * @return BelongsTo<Patient, $this>
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * The provider (doctor) the appointment is with.
     *
     * @return BelongsTo<User, $this>
     */
    public function provider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    /**
     * The facility the appointment takes place at.
     *
     * @return BelongsTo<Facility, $this>
     */
    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    /**
     * The duration of the appointment in minutes.
     */
    public function getDurationAttribute(): int
    {
        return (int) $this->starts_at->diffInMinutes($this->ends_at);
    }

    /**
     * Scope to appointments on a given date.
     *
     * @param  Builder<Appointment>  $query
     * @return Builder<Appointment>
     */
    public function scopeOnDate(Builder $query, string|Carbon|CarbonImmutable $date): Builder
    {
        return $query->whereBetween('starts_at', [
            Carbon::parse($date)->startOfDay(),
            Carbon::parse($date)->endOfDay(),
        ]);
    }

    /**
     * Scope to appointments that have not finished yet.
     *
     * @param  Builder<Appointment>  $query
     * @return Builder<Appointment>
     */
    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('starts_at', '>=', now());
    }

    /**
     * Scope to appointments that could still be in progress today.
     *
     * @param  Builder<Appointment>  $query
     * @return Builder<Appointment>
     */
    public function scopeWaiting(Builder $query): Builder
    {
        return $query->whereIn('status', AppointmentStatus::waitingStatuses());
    }

    /**
     * Determine if the appointment overlaps with the given time range for a provider.
     */
    public static function providerHasOverlap(
        ?int $providerId,
        Carbon|CarbonImmutable $startsAt,
        Carbon|CarbonImmutable $endsAt,
        ?int $exceptId = null,
    ): bool {
        if ($providerId === null) {
            return false;
        }

        return static::query()
            ->where('provider_id', $providerId)
            ->whereNot('status', AppointmentStatus::Cancelled->value)
            ->when($exceptId, fn (Builder $query) => $query->whereKeyNot($exceptId))
            ->where('starts_at', '<', $endsAt)
            ->where('ends_at', '>', $startsAt)
            ->exists();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'status' => AppointmentStatus::class,
        ];
    }
}
