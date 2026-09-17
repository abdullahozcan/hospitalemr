<?php

namespace App\Models;

use App\Concerns\BelongsToTeam;
use App\Enums\AllergySeverity;
use Database\Factories\AllergyFactory;
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
 * @property string $allergen
 * @property string|null $reaction
 * @property AllergySeverity|null $severity
 * @property Carbon|null $noted_at
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Patient $patient
 */
#[Fillable([
    'team_id',
    'patient_id',
    'allergen',
    'reaction',
    'severity',
    'noted_at',
    'notes',
])]
class Allergy extends Model
{
    use BelongsToTeam;

    /** @use HasFactory<AllergyFactory> */
    use HasFactory, SoftDeletes;

    /**
     * The patient with the allergy.
     *
     * @return BelongsTo<Patient, $this>
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'severity' => AllergySeverity::class,
            'noted_at' => 'datetime',
        ];
    }
}
