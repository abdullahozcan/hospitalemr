<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Models\User;
use App\Models\Vital;

class VitalPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(Permission::ViewEncounter->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Vital $vital): bool
    {
        return $user->can(Permission::ViewEncounter->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(Permission::UpdateEncounter->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Vital $vital): bool
    {
        return $user->can(Permission::UpdateEncounter->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Vital $vital): bool
    {
        return $user->can(Permission::UpdateEncounter->value);
    }
}
