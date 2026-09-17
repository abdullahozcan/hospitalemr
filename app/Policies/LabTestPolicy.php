<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Models\LabTest;
use App\Models\User;

class LabTestPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(Permission::ViewLabOrder->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, LabTest $labTest): bool
    {
        return $user->can(Permission::ViewLabOrder->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(Permission::CreateLabOrder->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, LabTest $labTest): bool
    {
        return $user->can(Permission::CreateLabOrder->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LabTest $labTest): bool
    {
        return $user->can(Permission::CreateLabOrder->value);
    }
}
