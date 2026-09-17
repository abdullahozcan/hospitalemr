<?php

namespace App\Concerns;

use App\Models\Team;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

/**
 * Scopes records to the current team and maintains the team_id column.
 *
 * The model must have a `team_id` column and a `team()` relation.
 *
 * @property int $team_id
 */
trait BelongsToTeam
{
    /**
     * Bootstrap the trait's hooks.
     */
    public static function bootBelongsToTeam(): void
    {
        static::creating(function (Model $model) {
            if (empty($model->getAttribute('team_id')) && Auth::check()) {
                $model->forceFill(['team_id' => Auth::user()->current_team_id]);
            }
        });

        static::addGlobalScope('team', function (Builder $query) {
            $teamId = static::currentTeamId();

            if ($teamId) {
                $query->where($query->getModel()->getTable().'.team_id', $teamId);
            }
        });
    }

    /**
     * Get the ID of the current team, if any.
     */
    public static function currentTeamId(): ?int
    {
        $user = Auth::user();

        return $user?->current_team_id;
    }

    /**
     * The clinic the record belongs to.
     *
     * @return BelongsTo<Team, $this>
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
