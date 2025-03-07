<?php

use App\Models\Task;
use App\Models\User;
use function Pest\Laravel\{actingAs, get, post, put};

beforeEach(function () {
    $this->userOne = User::factory()->create();
    $this->userTwo = User::factory()->create();
});

test('user cannot create a task without authentication', function () {
    $response = post('/tasks', [
        'title'       => 'Test Task',
        'description' => 'This is a test task.',
    ]);

    $response->assertRedirect('/login');
});

test('authenticated user can create a task', function () {
    actingAs($this->userOne);

    $response = post('/tasks', [
        'title'       => 'New Task',
        'description' => 'Task description',
    ]);

    $response->assertRedirect(route('tasks.index'));
    $this->assertDatabaseHas('tasks', ['title' => 'New Task']);
});

test('user can view only their own tasks', function () {
    actingAs($this->userOne);

    Task::factory()->count(2)->create(['user_id' => $this->userOne->id]);
    Task::factory()->count(2)->create(['user_id' => $this->userTwo->id]);

    $response = get('/tasks');
    $response->assertStatus(200);

    foreach (Task::where('user_id', $this->userOne->id)->get() as $task) {
        $response->assertSee($task->title);
    }

    foreach (Task::where('user_id', $this->userTwo->id)->get() as $task) {
        $response->assertDontSee($task->title);
    }
});

test('user can update their own task', function () {
    actingAs($this->userOne);

    $task = Task::factory()->create(['user_id' => $this->userOne->id]);

    $response = put("/tasks/{$task->id}", [
        'title'       => 'Updated Task',
        'description' => 'Updated description',
        'status'      => 'pending',
    ]);

    $response->assertRedirect(route('tasks.index'));
    $this->assertDatabaseHas('tasks', [
        'id'          => $task->id,
        'title'       => 'Updated Task',
        'description' => 'Updated description',
        'status'      => 'pending',
    ]);
});

test('user cannot update another user\'s task', function () {
    actingAs($this->userOne);

    $task = Task::factory()->create(['user_id' => $this->userTwo->id]);

    $response = put("/tasks/{$task->id}", [
        'title'       => 'Unauthorized Update',
        'description' => 'This should not work',
    ]);

    $response->assertForbidden();
});

test('user can delete their own task', function () {
    actingAs($this->userOne);

    $task = Task::factory()->create(['user_id' => $this->userOne->id]);

    $response = actingAs($this->userOne)->delete("/tasks/{$task->id}");

    $response->assertRedirect(route('tasks.index'));
    //$this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    $this->assertSoftDeleted('tasks', ['id' => $task->id]); // Sof Delete
});

test('user cannot delete another user\'s task', function () {
    actingAs($this->userOne);

    $task = Task::factory()->create(['user_id' => $this->userTwo->id]);

    $response = actingAs($this->userOne)->delete("/tasks/{$task->id}");

    $response->assertForbidden();
});
