<?php

namespace Tests\Feature\Http\Api\Passport;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->refreshDatabase();
        $this->seed();
    }

    public function test_index_positive(): void
    {
        /** @var User $user */
        $user = User::query()->first();
        Passport::actingAs($user);

        $response = $this->getJson("api/v2/tasks");

        $response->assertStatus(200);
    }

    public function test_index_negative(): void
    {
        $response = $this->getJson("api/v2/tasks");

        $response->assertStatus(401);
    }
}
