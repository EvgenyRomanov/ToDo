<?php

namespace App\Services;

use App\DTO\TaskDTO;
use App\Jobs\CreateTask;
use App\Models\Task;

class TaskService
{
    public function create(TaskDTO $taskDTO): Task
    {
        $task = Task::create([
            'title' => $taskDTO->title,
            'description' => $taskDTO->description,
            'completed' => $taskDTO->completed,
            'user_id' => $taskDTO->user->id
        ]);

        CreateTask::dispatch($task);

        return $task;
    }

    public function delete(int $taskId): int
    {
        return Task::destroy($taskId);
    }

    public function update(Task $task, TaskDTO $taskDTO): bool
    {
        return $task->update([
            'title' => $taskDTO->title,
            'description' => $taskDTO->description,
            'completed' => $taskDTO->completed,
            'user_id' => $taskDTO->user->id
        ]);
    }
}
