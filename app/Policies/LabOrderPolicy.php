<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Models\LabOrder;
use App\Models\User;

class LabOrderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(Permission::ViewAnyLabOrder->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, LabOrder $labOrder): bool
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
    public function update(User $user, LabOrder $labOrder): bool
    {
        return $user->can(Permission::CreateLabOrder->value);
    }

    /**
     * Determine whether the user can record laboratory results.
     */
    public function recordResult(User $user, LabOrder $labOrder): bool
    {
        return $user->can(Permission::RecordLabResult->value);
    }

    /**
     * Determine whether the user can review laboratory results.
     */
    public function review(User $user, LabOrder $labOrder): bool
    {
        return $user->can(Permission::ReviewLabResult->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LabOrder $labOrder): bool
    {
        return $user->can(Permission::CreateLabOrder->value);
    }
}
