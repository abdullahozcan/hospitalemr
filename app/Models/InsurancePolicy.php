<?php

namespace App\Models;

use App\Concerns\BelongsToTeam;
use Database\Factories\InsurancePolicyFactory;
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
 * @property string $company_name
 * @property string $policy_number
 * @property Carbon|null $start_date
 * @property Carbon|null $end_date
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Patient $patient
 */
#[Fillable([
    'team_id',
    'patient_id',
    'company_name',
    'policy_number',
    'start_date',
    'end_date',
    'notes',
])]
class InsurancePolicy extends Model
{
    use BelongsToTeam;

    /** @use HasFactory<InsurancePolicyFactory> */
    use HasFactory, SoftDeletes;

    /**
     * The patient this policy belongs to.
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
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }
}
