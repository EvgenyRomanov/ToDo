<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
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

    public function test_home(): void
    {
        $response = $this->get('/');

        $content = trans('home.welcome');
        $response->assertOk();
        $response->assertSeeText($content);
    }

    public function test_register_display(): void
    {
        $response = $this->get('/register');

        $response->assertOk();
        $response->assertViewIs("register");
    }

    public function test_register(): void
    {
        $response = $this->post("/register", [
            "name" => "test",
            "email" => "t@t.ru",
            "password" => "12345678",
            "password_confirmation" => "12345678",
        ]);

        $response->assertRedirect('/tasks');
    }

    public function test_register_negative(): void
    {
        $response = $this->post("/register", [
            "email" => "tt.ru",
            "password" => "12345678",
            "password_confirmation" => "12345678",
        ]);

        $response->assertSessionHasErrors(['name', 'email']);
    }

    public function test_login(): void
    {
        $response = $this->get("/login");

        $response->assertOk();
        $response->assertViewIs("login");
    }

    public function test_login_redirect(): void
    {
        $user = User::first();
        Auth::setUser($user);
        $response = $this->get("/login");

        $response->assertRedirect("/tasks");
    }

    public function test_authenticate(): void
    {
        $user = User::first();
        $response = $this->post("/login", [
            "email" => $user->email,
            "password" => "password",
            "password_confirmation" => "password",
        ]);

        $response->assertRedirect("/tasks");
    }

    public function test_authenticate_negative(): void
    {
        $response = $this->post("/login", [
            "email" => "",
            "password" => "password",
            "password_confirmation" => "password",
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_logout(): void
    {
        $user = User::first();
        $response = $this->actingAs($user)->get("/logout");

        $response->assertRedirect("/");
    }
}
