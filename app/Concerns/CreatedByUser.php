<?php

namespace App\Concerns;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

/**
 * Tracks which staff member created the record.
 */
trait CreatedByUser
{
    public static function bootCreatedByUser(): void
    {
        static::creating(function ($model) {
            if (empty($model->getAttribute('created_by')) && Auth::check()) {
                $model->forceFill(['created_by' => Auth::user()->id]);
            }
        });
    }

    /**
     * The user who created the record.
     *
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
