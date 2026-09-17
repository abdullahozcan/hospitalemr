<?php

namespace App\Models;

use Database\Factories\ApiAccessLogFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int|null $api_client_id
 * @property string $method
 * @property string $path
 * @property int|null $status_code
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read ApiClient|null $client
 */
#[Fillable([
    'api_client_id',
    'method',
    'path',
    'status_code',
])]
class ApiAccessLog extends Model
{
    /** @use HasFactory<ApiAccessLogFactory> */
    use HasFactory;

    /**
     * The client that made the request.
     *
     * @return BelongsTo<ApiClient, $this>
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(ApiClient::class, 'api_client_id');
    }
}
