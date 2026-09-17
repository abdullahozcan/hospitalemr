<?php

use App\Enums\UserRole;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;

beforeEach(fn () => $this->seed(RolePermissionSeeder::class));

test('guests are redirected to the admin login', function () {
    $this->get('/admin')->assertRedirect('/admin/login');
});

test('users without a role cannot access the admin panel', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get('/admin')->assertForbidden();
});

test('users with a role can access the admin panel', function (UserRole $role) {
    $user = User::factory()->withRole($role)->create();

    $this->actingAs($user)->get('/admin')->assertOk();
})->with(UserRole::cases());
