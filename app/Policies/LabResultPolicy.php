<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Models\LabResult;
use App\Models\User;

class LabResultPolicy
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
    public function view(User $user, LabResult $labResult): bool
    {
        return $user->can(Permission::ViewLabOrder->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(Permission::RecordLabResult->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, LabResult $labResult): bool
    {
        return $user->can(Permission::RecordLabResult->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LabResult $labResult): bool
    {
        return $user->can(Permission::RecordLabResult->value);
    }
}
