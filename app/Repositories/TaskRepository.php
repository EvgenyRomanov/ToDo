<?php

namespace App\Repositories;

use App\Models\Interfaces\TaskRepositoryInterface;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\LazyCollection;

class TaskRepository implements TaskRepositoryInterface
{
    public function getAllUsersTasks(int $userId): Collection
    {
        return Task::query()->where('user_id', $userId)->get();
    }

    public function findTask(int $taskId): ?Task
    {
        return Task::find($taskId);
    }

    public function getAllUsersTasksLazy(int $userId): LazyCollection
    {
        return Task::query()->where('user_id', $userId)->get();
    }
}
