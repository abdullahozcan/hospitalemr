<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\IntegrationLog;
use App\Models\User;

class IntegrationLogPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(UserRole::Admin->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, IntegrationLog $integrationLog): bool
    {
        return $user->hasRole(UserRole::Admin->value);
    }
}
