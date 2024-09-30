<?php

namespace Tests\Feature\Http\Api\Passport;

use App\Models\Interfaces\RolesInterfaces;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->refreshDatabase();
        $this->seed();
    }

    public function test_index_positive_user_admin(): void
    {
        /** @var User $userAdmin */
        $userAdmin = User::query()->create(
            [
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
            ]
        );
        RoleUser::query()->create(
            [
                'user_id' => $userAdmin->id,
                'role_id' => Role::query()
                    ->where('name', '=', RolesInterfaces::ADMIN)->first()->id
            ]
        );
        Passport::actingAs($userAdmin);

        $response = $this->getJson("api/v2/users");

        $response->assertStatus(200);
    }

    public function test_index_negative_user_not_admin(): void
    {
        /** @var User $user */
        $user = User::query()->create(
            [
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
            ]
        );
        RoleUser::query()->create(
            [
                'user_id' => $user->id,
                'role_id' => Role::query()
                    ->where('name', '=', RolesInterfaces::DEVELOPER)->first()->id
            ]
        );
        Passport::actingAs($user);

        $response = $this->getJson("api/v2/users");

        $response->assertStatus(403);
    }

    public function test_index_negative(): void
    {
        $response = $this->getJson("api/v2/users");

        $response->assertStatus(401);
    }

    public function test_show_positive(): void
    {
        /** @var User $user */
        $user = User::query()->first();
        Passport::actingAs($user);

        $response = $this->getJson("api/v2/users/{$user->id}");
        $response->assertOk();
    }

    public function test_show_negative(): void
    {
        /** @var User $user1 */
        $user1 = User::query()->first();
        /** @var User $user2 */
        $user2 = User::query()->create(
            [
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
            ]
        );
        RoleUser::query()->create(
            [
                'user_id' => $user2->id,
                'role_id' => Role::query()
                    ->where('name', '=', RolesInterfaces::DEVELOPER)->first()->id
            ]
        );
        Passport::actingAs($user2);

        $response = $this->getJson("api/v2/users/{$user1->id}");
        $response->assertStatus(403);
    }

    public function test_update()
    {
        /** @var User $user */
        $user = User::query()->first();
        Passport::actingAs($user);
        $nameExcepted = $user->name . '123';
        $response = $this->putJson("api/v2/users/{$user->id}", [
            'name' => $nameExcepted
        ]);
        $response->assertStatus(200);
        $user->refresh();
        $this->assertEquals($nameExcepted, $user->name);
    }

    public function test_delete()
    {
        /** @var User $user */
        $user = User::query()->first();
        Passport::actingAs($user);
        $response = $this->deleteJson("api/v2/users/{$user->id}");

        $response->assertStatus(204);
        $this->assertEquals(null, User::find($user->id));
    }

    public function test_create_positive()
    {
        /** @var User $userAdmin */
        $userAdmin = User::query()->create(
            [
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
            ]
        );
        RoleUser::query()->create(
            [
                'user_id' => $userAdmin->id,
                'role_id' => Role::query()
                    ->where('name', '=', RolesInterfaces::ADMIN)->first()->id
            ]
        );
        Passport::actingAs($userAdmin);

        $response = $this->postJson("api/v2/users", [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => 'password'
        ]);
        $response->assertStatus(201);
    }

    public function test_create_negative()
    {
        /** @var User $user */
        $user = User::query()->create(
            [
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
            ]
        );
        RoleUser::query()->create(
            [
                'user_id' => $user->id,
                'role_id' => Role::query()
                    ->where('name', '=', RolesInterfaces::DEVELOPER)->first()->id
            ]
        );
        Passport::actingAs($user);

        $response = $this->postJson("api/v2/users", [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => 'password'
        ]);
        $response->assertStatus(403);
    }
}
