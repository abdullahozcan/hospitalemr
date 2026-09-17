<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Models\BillingLine;
use App\Models\User;

class BillingLinePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(Permission::ViewAnyBilling->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, BillingLine $billingLine): bool
    {
        return $user->can(Permission::ViewBilling->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(Permission::CreateBilling->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, BillingLine $billingLine): bool
    {
        return $user->can(Permission::UpdateBilling->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BillingLine $billingLine): bool
    {
        return $user->can(Permission::DeleteBilling->value);
    }
}
