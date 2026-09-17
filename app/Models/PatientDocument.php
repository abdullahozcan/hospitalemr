<?php

namespace App\Models;

use App\Concerns\BelongsToTeam;
use App\Concerns\CreatedByUser;
use Database\Factories\PatientDocumentFactory;
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
 * @property string $title
 * @property string $file_path
 * @property int $created_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Patient $patient
 * @property-read User $creator
 */
#[Fillable([
    'team_id',
    'patient_id',
    'title',
    'file_path',
    'created_by',
])]
class PatientDocument extends Model
{
    use BelongsToTeam, CreatedByUser;

    /** @use HasFactory<PatientDocumentFactory> */
    use HasFactory, SoftDeletes;

    /**
     * The patient this document belongs to.
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
        return [];
    }
}
