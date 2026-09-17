<?php

use App\Enums\UserRole;
use App\Filament\Resources\Facilities\Pages\ListFacilities;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Models\Facility;
use App\Models\Team;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Livewire\Livewire;

beforeEach(fn () => $this->seed(RolePermissionSeeder::class));

test('admins can view the facility list', function () {
    $user = User::factory()->withRole(UserRole::Admin)->create();
    $this->actingAs($user);

    $facility = Facility::factory()->create(['team_id' => $user->current_team_id]);

    Livewire::test(ListFacilities::class)->assertCanSeeTableRecords([$facility]);
});

test('accountants cannot create facilities', function () {
    $user = User::factory()->withRole(UserRole::Accountant)->create();

    expect($user->can('create', Facility::class))->toBeFalse();
});

test('only admins can manage users', function () {
    $admin = User::factory()->withRole(UserRole::Admin)->create();
    $doctor = User::factory()->withRole(UserRole::Doctor)->create();

    expect($admin->can('viewAny', User::class))->toBeTrue()
        ->and($admin->can('create', User::class))->toBeTrue()
        ->and($doctor->can('viewAny', User::class))->toBeFalse()
        ->and($doctor->can('update', $admin))->toBeFalse();
});

test('admins can see the user list with roles', function () {
    $user = User::factory()->withRole(UserRole::Admin)->create();
    $this->actingAs($user);

    $doctor = User::factory()->withRole(UserRole::Doctor)->create();

    Livewire::test(ListUsers::class)->assertCanSeeTableRecords([$doctor]);
});

test('facilities from other teams are not listed', function () {
    $user = User::factory()->withRole(UserRole::Admin)->create();
    $this->actingAs($user);

    $own = Facility::factory()->create(['team_id' => $user->current_team_id]);
    Facility::factory()->create();

    Livewire::test(ListFacilities::class)->assertCanSeeTableRecords([$own]);
});

test('deletion of own account is prevented', function () {
    $admin = User::factory()->withRole(UserRole::Admin)->create();

    expect($admin->can('delete', $admin))->toBeFalse();
});

test('facility model belongs to team', function () {
    $team = Team::factory()->create();
    $facility = Facility::factory()->create(['team_id' => $team->id]);

    expect($facility->team->id)->toBe($team->id);
});
