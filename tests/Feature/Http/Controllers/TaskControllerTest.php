<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Services\TelegramNotification\TelegramNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->refreshDatabase();
        $this->seed();
    }

    public function test_index(): void
    {
        $user = User::first();
        $response = $this->actingAs($user)->get('/tasks');

        $content = trans('task.task_dashboard');
        $response->assertOk();
        $response->assertSeeText($content);
    }

    public function test_create(): void
    {
        $user = User::first();
        $response = $this->actingAs($user)->get('/tasks/create');

        $response->assertOk();
        $response->assertViewIs("tasks.create");
    }

    public function test_show(): void
    {
        $task = Task::first();
        $user = $task->user;
        $response = $this->actingAs($user)->get("/tasks/{$task->id}");

        $response->assertOk();
        $response->assertSeeText($task->title);
    }

    public function test_edit(): void
    {
        $task = Task::first();
        $user = $task->user;
        $response = $this->actingAs($user)->get("/tasks/{$task->id}/edit");

        $response->assertOk();
        $response->assertViewIs("tasks.edit");
    }

    public function test_store(): void
    {
        $this->instance(
            TelegramNotification::class,
            Mockery::mock(TelegramNotification::class, function (MockInterface $mock) {
                $mock->shouldReceive('sendMessage')->once();
            })
        );

        $user = User::first();
        $response = $this->actingAs($user)->post("/tasks", [
            'title' => fake()->sentence,
            'description' => fake()->text,
        ]);

        $response->assertRedirect("/tasks");
    }

    public function test_update(): void
    {
        $task = Task::first();
        $user = $task->user;
        $response = $this->actingAs($user)->put("/tasks/{$task->id}", [
            'title' => fake()->sentence,
            'description' => fake()->text,
        ]);

        $response->assertRedirect("/tasks");
    }

    public function test_store_negative(): void
    {
        $user = User::first();
        $response = $this->actingAs($user)->post("/tasks", [
            'title' => "12345",
        ]);

        $response->assertSessionHasErrors(['title', 'description']);
    }

    public function test_update_negative(): void
    {
        $task = Task::first();
        $user = $task->user;
        $response = $this->actingAs($user)->put("/tasks/{$task->id}", [
            'title' => "123456",
            'description' => "12345",
        ]);

        $response->assertSessionHasErrors(['description']);
    }
}
