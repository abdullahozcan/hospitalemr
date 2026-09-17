<?php

namespace App\Models;

use App\Concerns\BelongsToTeam;
use Database\Factories\ApiClientFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\PersonalAccessToken;

/**
 * @property int $id
 * @property int $team_id
 * @property string $name
 * @property string|null $client_code
 * @property bool $is_active
 * @property Carbon|null $last_used_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection<int, PersonalAccessToken> $tokens
 */
#[Fillable([
    'team_id',
    'name',
    'client_code',
    'is_active',
    'last_used_at',
])]
class ApiClient extends Model
{
    use BelongsToTeam;
    use HasApiTokens;

    /** @use HasFactory<ApiClientFactory> */
    use HasFactory;

    use SoftDeletes;

    /**
     * The token ability granted for read-only FHIR access.
     */
    public const FHIR_READ_ABILITY = 'fhir.read';

    /**
     * The access tokens issued for this client.
     *
     * @return HasMany<PersonalAccessToken, $this>
     */
    public function accessTokens(): HasMany
    {
        return $this->hasMany(PersonalAccessToken::class, 'tokenable_id')
            ->where('tokenable_type', self::class);
    }

    /**
     * The access logs recorded for this client.
     *
     * @return HasMany<ApiAccessLog, $this>
     */
    public function accessLogs(): HasMany
    {
        return $this->hasMany(ApiAccessLog::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'last_used_at' => 'datetime',
        ];
    }
}
