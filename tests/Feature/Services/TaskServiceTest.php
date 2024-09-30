<?php

namespace Tests\Feature\Services;

use App\DTO\TaskDTO;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use App\Services\TelegramNotification\TelegramNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class TaskServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->refreshDatabase();
        $this->seed();
    }

    public function test_create(): void
    {
        $this->instance(
            TelegramNotification::class,
            Mockery::mock(TelegramNotification::class, function (MockInterface $mock) {
                $mock->shouldReceive('sendMessage')->once();
            })
        );

        $DTO = new TaskDTO(
            "title",
            'description',
            0,
            User::first()
        );
        $service = new TaskService();

        $this->assertTrue($service->create($DTO) instanceof Task);
    }

    public function test_delete(): void
    {
        $task = Task::first();
        $service = new TaskService();
        $service->delete($task->id);

        $this->assertTrue(Task::find($task->id) === null);
    }

    public function test_update(): void
    {
        $task = Task::first();
        $service = new TaskService();
        $DTO = new TaskDTO(
            "title",
            'description',
            0,
            User::find($task->user_id)
        );

        $this->assertTrue($service->update($task, $DTO));
    }
}
