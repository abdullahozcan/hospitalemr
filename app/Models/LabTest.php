<?php

namespace App\Models;

use App\Concerns\BelongsToTeam;
use Database\Factories\LabTestFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $team_id
 * @property string|null $code
 * @property string $name
 * @property string|null $category
 * @property string|null $sample_type
 * @property string|null $unit
 * @property float|null $reference_min
 * @property float|null $reference_max
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection<int, LabResult> $labResults
 */
#[Fillable([
    'team_id',
    'code',
    'name',
    'category',
    'sample_type',
    'unit',
    'reference_min',
    'reference_max',
    'is_active',
])]
class LabTest extends Model
{
    use BelongsToTeam;

    /** @use HasFactory<LabTestFactory> */
    use HasFactory, SoftDeletes;

    /**
     * The results recorded for this test.
     *
     * @return HasMany<LabResult, $this>
     */
    public function labResults(): HasMany
    {
        return $this->hasMany(LabResult::class);
    }

    /**
     * Scope to active tests.
     *
     * @param  Builder<LabTest>  $query
     * @return Builder<LabTest>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'reference_min' => 'decimal:2',
            'reference_max' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }
}
