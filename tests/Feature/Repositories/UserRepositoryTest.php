<?php

namespace Tests\Feature\Repositories;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->refreshDatabase();
        $this->seed();
    }

    public function test_find_user(): void
    {
        $user = User::first();
        $repository = new UserRepository();
        $findUser = $repository->findUser($user->id);

        $this->assertTrue($user == $findUser);
    }

    public function test_find_user_not_found(): void
    {
        $repository = new UserRepository();
        $findUser = $repository->findUser(0);

        $this->assertTrue(null === $findUser);
    }
}
