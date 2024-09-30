<?php

namespace App\Models\Interfaces;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\LazyCollection;

interface TaskRepositoryInterface
{
    public function getAllUsersTasks(int $userId): Collection;

    public function findTask(int $taskId): ?Task;

    public function getAllUsersTasksLazy(int $userId): LazyCollection;
}
