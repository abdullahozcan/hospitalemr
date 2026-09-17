<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Models\Drug;
use App\Models\User;

class DrugPolicy
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
    public function view(User $user, Drug $drug): bool
    {
        return $user->can(Permission::ViewPatient->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(Permission::CreatePrescription->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Drug $drug): bool
    {
        return $user->can(Permission::CreatePrescription->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Drug $drug): bool
    {
        return $user->can(Permission::CreatePrescription->value);
    }
}
