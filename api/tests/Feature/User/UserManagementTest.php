<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Database\Seeders\UserSeeder;
use Illuminate\Support\Facades\Artisan;

beforeEach(function () {
    Artisan::call('db:wipe');
    Artisan::call('migrate');
    (new UserSeeder())->run();
});


test('an admin can create a new user', function () {
    $admin = User::where('is_admin', true)->first();
    Sanctum::actingAs($admin);

    $userData = [
        'name' => 'New User',
        'username' => 'newuser',
        'email' => 'new@example.com',
        'password' => 'password123',
    ];

    $this->postJson('/api/user', $userData)
        ->assertStatus(201)
        ->assertJsonFragment(['email' => 'new@example.com']);

    $this->assertDatabaseHas('users', [
        'username' => 'newuser',
        'email' => 'new@example.com',
    ]);
});

test('a user can register', function () {
    $userData = [
        'name' => 'Another User',
        'username' => 'anotheruser',
        'email' => 'another@example.com',
        'password' => 'password123',
    ];

    $this->postJson('/api/user', $userData)->assertCreated();
});


test('user creation fails with validation errors', function () {
    $admin = User::where('is_admin', true)->first();
    Sanctum::actingAs($admin);

    $this->postJson('/api/user', [])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'username', 'email', 'password']);
});
