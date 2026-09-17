<?php

namespace App\Models;

use App\Concerns\BelongsToTeam;
use Database\Factories\ServiceFactory;
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
 * @property string|null $description
 * @property float $price
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection<int, BillingLine> $billingLines
 */
#[Fillable([
    'team_id',
    'code',
    'name',
    'description',
    'price',
    'is_active',
])]
class Service extends Model
{
    use BelongsToTeam;

    /** @use HasFactory<ServiceFactory> */
    use HasFactory, SoftDeletes;

    /**
     * The billing lines that use this service.
     *
     * @return HasMany<BillingLine, $this>
     */
    public function billingLines(): HasMany
    {
        return $this->hasMany(BillingLine::class);
    }

    /**
     * Scope to active services.
     *
     * @param  Builder<Service>  $query
     * @return Builder<Service>
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
            'price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }
}
