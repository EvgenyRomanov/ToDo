<?php

namespace Tests\Feature\Http\Api\JWT;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
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

    public function test_index_negative(): void
    {
        $response = $this->withHeaders(
            [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer 123'
            ]
        )->get('api/v1/users');

        $response->assertStatus(401);
    }

    public function test_index_positive(): void
    {
        /** @var User $user */
        $user = User::query()->first();
        $response = $this->actingAs($user, 'jwt')->getJson('api/v1/users');

        $response->assertOk();
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('data')
        );
    }
}
