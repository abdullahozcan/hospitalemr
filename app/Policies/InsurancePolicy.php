<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Models\InsurancePolicy;
use App\Models\User;

class InsurancePolicyPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(Permission::ViewPatient->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, InsurancePolicy $insurancePolicy): bool
    {
        return $user->can(Permission::ViewPatient->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(Permission::UpdatePatient->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, InsurancePolicy $insurancePolicy): bool
    {
        return $user->can(Permission::UpdatePatient->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, InsurancePolicy $insurancePolicy): bool
    {
        return $user->can(Permission::UpdatePatient->value);
    }
}
