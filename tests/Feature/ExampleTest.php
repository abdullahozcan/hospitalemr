<?php

use Database\Seeders\RolePermissionSeeder;

beforeEach(fn () => $this->seed(RolePermissionSeeder::class));

test('returns a successful response', function () {
    $response = $this->get(route('home'));

    $response->assertOk();
});
