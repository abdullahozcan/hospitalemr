<?php

use App\Enums\Permission;
use App\Enums\UserRole;
use Database\Seeders\RolePermissionSeeder;
use Spatie\Permission\Models\Role;

beforeEach(fn () => $this->seed(RolePermissionSeeder::class));

test('roles exist after seeding', function () {
    foreach (UserRole::values() as $role) {
        expect(Role::where('name', $role)->where('guard_name', 'web')->exists())->toBeTrue();
    }
});

test('each role has the permissions defined by its enum', function (UserRole $userRole) {
    $role = Role::findByName($userRole->value, 'web');
    $expected = collect($userRole->permissions())->map(fn (Permission $permission) => $permission->value)->all();

    expect($role->permissions->pluck('name')->sort()->values()->all())
        ->toBe(collect($expected)->sort()->values()->all());
})->with(UserRole::cases());

test('the admin role has every permission', function () {
    $role = Role::findByName(UserRole::Admin->value, 'web');

    expect($role->permissions->pluck('name')->sort()->values()->all())
        ->toBe(collect(Permission::values())->sort()->values()->all());
});

test('re-seeding keeps role permissions in sync', function () {
    $role = Role::findByName(UserRole::Doctor->value, 'web');
    $role->givePermissionTo(Permission::DeleteFacility->value);

    $this->seed(RolePermissionSeeder::class);

    $role->refresh();

    expect($role->hasPermissionTo(Permission::DeleteFacility->value))->toBeFalse();
});
