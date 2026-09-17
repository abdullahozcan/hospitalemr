<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Models\Facility;
use App\Models\User;

class FacilityPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(Permission::ViewAnyFacility->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Facility $facility): bool
    {
        return $user->can(Permission::ViewFacility->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(Permission::CreateFacility->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Facility $facility): bool
    {
        return $user->can(Permission::UpdateFacility->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Facility $facility): bool
    {
        return $user->can(Permission::DeleteFacility->value);
    }
}
