<?php

namespace App\Models;

use App\Concerns\BelongsToTeam;
use Database\Factories\IntegrationLogFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $team_id
 * @property string $service
 * @property string $operation
 * @property Model|null $subject
 * @property string $status
 * @property string|null $code
 * @property string|null $message
 * @property array<string, mixed>|null $request_payload
 * @property array<string, mixed>|null $response_payload
 * @property int $attempts
 * @property Carbon|null $sent_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'team_id',
    'service',
    'operation',
    'subject_type',
    'subject_id',
    'status',
    'code',
    'message',
    'request_payload',
    'response_payload',
    'attempts',
    'sent_at',
])]
class IntegrationLog extends Model
{
    use BelongsToTeam;

    /** @use HasFactory<IntegrationLogFactory> */
    use HasFactory;

    public const STATUS_PENDING = 'pending';

    public const STATUS_SUCCESS = 'success';

    public const STATUS_FAILED = 'failed';

    public const SERVICE_ENABIZ = 'enabiz';

    public const SERVICE_SGK = 'sgk';

    /**
     * The subject of the integration entry.
     *
     * @return MorphTo<Model, $this>
     */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'request_payload' => 'array',
            'response_payload' => 'array',
            'attempts' => 'integer',
            'sent_at' => 'datetime',
        ];
    }
}
