<?php

namespace App\Models;

use App\Concerns\BelongsToTeam;
use Database\Factories\DrugFactory;
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
 * @property string|null $active_ingredient
 * @property string|null $form
 * @property string|null $strength
 * @property string|null $unit
 * @property bool $is_prescription_only
 * @property int $stock_quantity
 * @property int $low_stock_threshold
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read bool $is_low_stock
 * @property-read string $display_name
 * @property-read Collection<int, PrescriptionItem> $prescriptionItems
 */
#[Fillable([
    'team_id',
    'code',
    'name',
    'active_ingredient',
    'form',
    'strength',
    'unit',
    'is_prescription_only',
    'stock_quantity',
    'low_stock_threshold',
    'notes',
])]
class Drug extends Model
{
    use BelongsToTeam;

    /** @use HasFactory<DrugFactory> */
    use HasFactory, SoftDeletes;

    /**
     * The prescription lines that reference this drug.
     *
     * @return HasMany<PrescriptionItem, $this>
     */
    public function prescriptionItems(): HasMany
    {
        return $this->hasMany(PrescriptionItem::class);
    }

    /**
     * Whether the stock is below the configured threshold.
     */
    public function getIsLowStockAttribute(): bool
    {
        return $this->stock_quantity <= $this->low_stock_threshold;
    }

    /**
     * The display name combining strength and form.
     */
    public function getDisplayNameAttribute(): string
    {
        $parts = [$this->name];

        if ($this->strength) {
            $parts[] = $this->strength;
        }

        if ($this->form) {
            $parts[] = $this->form;
        }

        return implode(' ', $parts);
    }

    /**
     * Scope to drugs whose stock is at or below the threshold.
     *
     * @param  Builder<Drug>  $query
     * @return Builder<Drug>
     */
    public function scopeLowStock(Builder $query): Builder
    {
        return $query->whereColumn('stock_quantity', '<=', 'low_stock_threshold');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_prescription_only' => 'boolean',
        ];
    }
}
