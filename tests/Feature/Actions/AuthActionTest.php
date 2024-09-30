<?php

namespace Tests\Feature\Actions;

use App\Actions\AuthAction;
use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\AuthManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class AuthActionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->refreshDatabase();
    }

    public function test_invoke_positive(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->make(['user_id' => $user->id]);
        $action = new AuthAction();

        $authManager = app()->get(AuthManager::class);
        $authManager->setUser($user);
        $action($task, $authManager);

        $this->assertTrue(true);
    }

    public function test_invoke_negative(): void
    {
        $this->expectException(HttpException::class);
        $this->expectExceptionMessage("Unauthorized Action");

        $users = User::factory(2)->create();
        $task = Task::factory()->make(['user_id' => $users->first()->id]);
        $action = new AuthAction();

        $authManager = app()->get(AuthManager::class);
        $authManager->setUser($users->last());
        $action($task, $authManager);
    }
}
