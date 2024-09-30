<?php

namespace App\Actions;

use App\Models\Task;
use Illuminate\Auth\AuthManager;

class AuthAction
{
    public function __invoke(Task $task, AuthManager $authManager): void
    {
        if ($task->user_id !== $authManager->id()) {
            abort(403, "Unauthorized Action");
        }
    }
}
