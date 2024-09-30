<?php

namespace Tests\Feature\Http\Api\JWT;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->refreshDatabase();
        $this->seed();
    }

    public function test_login_positive(): void
    {
        /** @var User $user */
        $user = User::query()->first();

        $response = $this->postJson(
            "api/v1/login",
            ['email' => $user->email, 'password' => 'password'],
        );

        $response->assertStatus(200);
        $response->assertJson(
            fn (AssertableJson $json) => $json
                ->has('access_token')
        );
    }

    public function test_login_negative(): void
    {
        /** @var User $user */
        $user = User::query()->first();

        $response = $this->post(
            "api/v1/login",
            ['email' => $user->email, 'password' => 'password1'],
        );

        $response->assertStatus(401);
    }
}
