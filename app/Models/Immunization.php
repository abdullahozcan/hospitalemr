<?php

namespace App\Models;

use App\Concerns\BelongsToTeam;
use Database\Factories\ImmunizationFactory;
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
 * @property string $vaccine_name
 * @property int|null $dose_number
 * @property Carbon $administered_at
 * @property int|null $administered_by
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Patient $patient
 * @property-read User|null $administrator
 */
#[Fillable([
    'team_id',
    'patient_id',
    'vaccine_name',
    'dose_number',
    'administered_at',
    'administered_by',
    'notes',
])]
class Immunization extends Model
{
    use BelongsToTeam;

    /** @use HasFactory<ImmunizationFactory> */
    use HasFactory, SoftDeletes;

    /**
     * The patient immunized.
     *
     * @return BelongsTo<Patient, $this>
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * The staff member who administered the vaccine.
     *
     * @return BelongsTo<User, $this>
     */
    public function administrator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'administered_by');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'administered_at' => 'datetime',
        ];
    }
}
