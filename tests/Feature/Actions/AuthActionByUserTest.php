<?php

namespace Tests\Feature\Actions;

use App\Actions\AuthActionByUser;
use App\Models\Interfaces\RolesInterfaces;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
use Illuminate\Auth\AuthManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class AuthActionByUserTest extends TestCase
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
        $action = new AuthActionByUser();

        $authManager = app()->get(AuthManager::class);
        $authManager->setUser($user);
        $action($user, $authManager);

        $this->assertTrue(true);
    }

    public function test_invoke_negative(): void
    {
        $this->expectException(HttpException::class);
        $this->expectExceptionMessage("Unauthorized Action");

        /** @var User $user1 */
        $user1 = User::query()->create(
            [
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
            ]
        );
        /** @var User $user2 */
        $user2 = User::factory()->create();
        RoleUser::query()->create(
            [
                'user_id' => $user1->id,
                'role_id' => Role::query()
                    ->where('name', '=', RolesInterfaces::DEVELOPER)->first()->id
            ]
        );

        $action = new AuthActionByUser();

        $authManager = app()->get(AuthManager::class);
        $authManager->setUser($user1);
        $action($user2, $authManager);
    }
}
