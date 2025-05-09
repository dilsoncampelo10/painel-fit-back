<?php

namespace Tests\Feature\app\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    private string $loginRoute;
    private string $logoutRoute;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'dilson@gmail.com',
            'password' => Hash::make('senha-do-dilson'),
        ]);

        $this->loginRoute = TestCase::api('login');
        $this->logoutRoute = TestCase::api('logout');
    }


    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->postJson($this->loginRoute, [
            'email' => 'naoexiste@example.com',
            'password' => 'senhaerrada'
        ]);

        $response->assertStatus(403);
    }

    public function test_login_returns_token_and_user_on_valid_credentials()
    {
        $response = $this->postJson($this->loginRoute, [
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
        $response = $this->postJson($this->loginRoute, [
            'password' => 'senha12345'
        ]);

        $response->assertStatus(422);
    }

    public function test_login_fails_with_unprocessable_content_when_password_is_missing()
    {
        $response = $this->postJson($this->loginRoute, [
            'email' => 'dilson@gmail.com'
        ]);

        $response->assertStatus(422);
    }

    public function test_logout_returns_status_code_200()
    {
        $loginResponse = $this->postJson($this->loginRoute, [
            'email' => 'dilson@gmail.com',
            'password' => 'senha-do-dilson'
        ]);

        $token = $loginResponse->json('token');

        $logoutResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson($this->logoutRoute);

        $logoutResponse->assertStatus(200);
    }

    public function test_logout_without_token_returns_unauthorized_401()
    {
        $response = $this->deleteJson($this->logoutRoute);

        $response->assertStatus(401);
    }

    public function test_logout_revokes_token()
    {
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->deleteJson($this->logoutRoute)
            ->assertStatus(200);

        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $this->user->id,
        ]);
    }
}
