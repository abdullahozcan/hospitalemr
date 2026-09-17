<?php

namespace App\Models;

use Database\Factories\IcdCodeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $code
 * @property string $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read string $full_description
 */
class IcdCode extends Model
{
    /** @use HasFactory<IcdCodeFactory> */
    use HasFactory;

    /**
     * The encounters this code was diagnosed in.
     *
     * @return BelongsToMany<Encounter, $this>
     */
    public function encounters(): BelongsToMany
    {
        return $this->belongsToMany(Encounter::class, 'encounter_diagnoses')
            ->withPivot(['is_primary', 'notes'])
            ->withTimestamps();
    }

    /**
     * The display text combining code and description.
     */
    public function getFullDescriptionAttribute(): string
    {
        return $this->code.' — '.$this->description;
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
