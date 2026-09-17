<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\ApiClient;
use App\Models\User;

class ApiClientPolicy
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
    public function view(User $user, ApiClient $apiClient): bool
    {
        return $user->hasRole(UserRole::Admin->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(UserRole::Admin->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ApiClient $apiClient): bool
    {
        return $user->hasRole(UserRole::Admin->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ApiClient $apiClient): bool
    {
        return $user->hasRole(UserRole::Admin->value);
    }
}
