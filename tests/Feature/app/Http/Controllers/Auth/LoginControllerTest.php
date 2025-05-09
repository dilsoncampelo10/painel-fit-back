<?php

namespace Tests\Feature\app\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'naoexiste@example.com',
            'password' => 'senhaerrada'
        ]);

        $response->assertStatus(403);
    }

    public function test_login_returns_token_and_user_on_valid_credentials()
    {
        User::factory()->create([
            'email' => 'dilson@gmail.com',
            'password' => Hash::make('senha-do-dilson'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'dilson@gmail.com',
            'password' => 'senha-do-dilson'
        ]);

        $response->assertStatus(201)
            ->assertExactJsonStructure([
                'user' => [
                    'id',
                    'name',
                    'email',
                    'email_verified_at',
                    'created_at',
                    'updated_at',
                    'deleted_at'
                ],
                'token'
            ]);
    }

    public function test_login_fails_with_unprocessable_content_when_email_is_missing()
    {
        $response = $this->postJson('/api/login', [
            'password' => 'senha12345'
        ]);

        $response->assertStatus(422);
    }

    public function test_login_fails_with_unprocessable_content_when_password_is_missing()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'dilson@gmail.com'
        ]);

        $response->assertStatus(422);
    }
}
