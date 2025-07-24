<?php

namespace Tests\Feature\Task;

use App\Models\Task;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create(['is_admin' => 1]);
    $this->test_user = User::factory()->create(['is_admin' => 0]);
    Sanctum::actingAs($this->user);
});

test('an authenticated user can create a task', function () {
    $taskData = [
        'title' => 'My First Task',
        'description' => 'A description for the task.',
        'due_date' => now()->addDays(7)->toDateString(),
        'user_id' => $this->test_user->id,
    ];

    $this->postJson('/api/task', $taskData)
        ->assertCreated() // 201
        ->assertJsonFragment(['title' => 'My First Task']);

    $this->assertDatabaseHas('tasks', [
        'user_id' => $this->test_user->id,
        'title' => 'My First Task',
    ]);
});

test('an authenticated user can list their own tasks', function () {
    Task::factory()->count(3)->create(['user_id' => $this->user->id]);
    Task::factory()->create(['user_id' => $this->test_user->id]);

    $this->getJson('/api/task')
        ->assertOk()
        ->assertJsonCount(4, 'data');
});

test('an authenticated user can update their own task', function () {
    $task = Task::factory()->create(['user_id' => $this->user->id]);

    $updateData = [
        'title' => 'Updated Task Title',
        'status' => 'completed',
    ];

    $this->putJson("/api/task/{$task->id}", $updateData)
        ->assertOk()
        ->assertJsonFragment(['title' => 'Updated Task Title']);

    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'title' => 'Updated Task Title',
    ]);
});

test('an authenticated user can delete their own task', function () {
    $task = Task::factory()->create(['user_id' => $this->user->id]);

    $this->deleteJson("/api/task/{$task->id}")
        ->assertNoContent(); // 204

    $this->assertSoftDeleted('tasks', ['id' => $task->id]);
});

test('task creation fails with validation errors', function () {
    $this->postJson('/api/task', ['description' => 'A task without a title'])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['title']);
});

test('a user cannot update another user\'s task', function () {
    Sanctum::actingAs($this->test_user);

    $otherUsersTask = Task::factory()->create(['user_id' => $this->user->id]);

    $this->putJson("/api/task/{$otherUsersTask->id}", ['title' => 'New Title'])
        ->assertForbidden(); // 403
});
