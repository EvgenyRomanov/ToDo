<?php

namespace Tests\Feature\Repositories;

use App\Models\Task;
use App\Repositories\TaskRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->refreshDatabase();
        $this->seed();
    }

    public function test_get_all_users_tasks(): void
    {
        $task = Task::first();
        $repository = new TaskRepository();
        $tasks = $repository->getAllUsersTasks($task->user_id);

        $this->assertTrue($tasks->contains($task));
    }

    public function test_find_task(): void
    {
        $task = Task::first();
        $repository = new TaskRepository();
        $findTask = $repository->findTask($task->id);

        $this->assertTrue($task == $findTask);
    }

    public function test_find_task_not_found(): void
    {
        $id = 0;
        $repository = new TaskRepository();
        $findTask = $repository->findTask($id);

        $this->assertTrue(null === $findTask);
    }
}
