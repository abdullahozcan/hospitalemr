<?php

use App\Enums\TeamRole;
use App\Enums\UserRole;
use App\Models\Facility;
use App\Models\Team;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;

beforeEach(fn () => $this->seed(RolePermissionSeeder::class));

test('creating a facility from the factory uses the factory team', function () {
    $facility = Facility::factory()->create();

    expect($facility->team)->toBeInstanceOf(Team::class);
});

test('creating a facility while authenticated assigns the current team', function () {
    $user = User::factory()->withRole(UserRole::Admin)->create();
    $this->actingAs($user);

    $facility = Facility::create(['name' => 'Test Clinic']);

    expect($facility->team_id)->toBe($user->current_team_id);
});

test('facility queries are scoped to the current team', function () {
    $teamA = Team::factory()->create();
    $teamB = Team::factory()->create();

    $facilityA = Facility::factory()->create(['team_id' => $teamA->id]);
    $facilityB = Facility::factory()->create(['team_id' => $teamB->id]);

    $user = User::factory()->create();
    $teamA->members()->attach($user, ['role' => TeamRole::Member->value]);
    $user->switchTeam($teamA);
    $this->actingAs($user);

    expect(Facility::all()->pluck('id'))->toContain($facilityA->id)
        ->and(Facility::all()->pluck('id'))->not->toContain($facilityB->id);
});

test('facility queries are unscoped when no user is authenticated', function () {
    $teamA = Team::factory()->create();
    $teamB = Team::factory()->create();

    Facility::factory()->create(['team_id' => $teamA->id]);
    Facility::factory()->create(['team_id' => $teamB->id]);

    expect(Facility::count())->toBe(2);
});
