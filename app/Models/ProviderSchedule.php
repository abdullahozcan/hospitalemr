<?php

namespace App\Models;

use App\Concerns\BelongsToTeam;
use Database\Factories\ProviderScheduleFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $team_id
 * @property int $user_id
 * @property int|null $facility_id
 * @property int $day_of_week
 * @property string $starts_at
 * @property string $ends_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read User $provider
 * @property-read Facility|null $facility
 */
#[Fillable([
    'team_id',
    'user_id',
    'facility_id',
    'day_of_week',
    'starts_at',
    'ends_at',
])]
class ProviderSchedule extends Model
{
    use BelongsToTeam;

    /** @use HasFactory<ProviderScheduleFactory> */
    use HasFactory;

    /**
     * The provider this schedule belongs to.
     *
     * @return BelongsTo<User, $this>
     */
    public function provider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * The facility this schedule is at.
     *
     * @return BelongsTo<Facility, $this>
     */
    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }
}
