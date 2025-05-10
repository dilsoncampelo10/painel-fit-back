<?php

namespace Tests\Feature\app\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Support\Str;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $registerRoute;

    protected function setUp(): void
    {
        parent::setUp();

        $this->registerRoute = self::api('register');
    }

    public function test_user_creation_fails_when_password_confirmation_does_not_match()
    {
        $response =  $this->postJson($this->registerRoute, [
            'name' => 'Dilson',
            'email' => 'dilson@gmail.com',
            'password' => '12345678',
            'password_confirmation' => '1234567'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password'])
            ->assertJsonFragment([
                'message' => 'The password field confirmation does not match.'
            ]);
    }

    public function test_user_creation_fails_when_email_is_missing()
    {
        $response =  $this->postJson($this->registerRoute, [
            'name' => 'Dilson',
            'password' => '12345678',
            'password_confirmation' => '12345678'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email'])
            ->assertJsonFragment([
                'message' => 'The email field is required.'
            ]);
    }

    public function test_user_creation_fails_when_name_is_missing()
    {
        $response =  $this->postJson($this->registerRoute, [
            'email' => 'dilson@gmail.com',
            'password' => '12345678',
            'password_confirmation' => '12345678'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name'])
            ->assertJsonFragment([
                'message' => 'The name field is required.'
            ])
        ;
    }

    public function test_user_creation_fails_when_passwords_is_missing()
    {
        $response =  $this->postJson($this->registerRoute, [
            'email' => 'dilson@gmail.com',
            'name' => 'Dilson',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password'])
            ->assertJsonFragment([
                'message' => 'The password field is required.'
            ])
        ;
    }
    public function test_user_registration_fails_when_email_already_exists()
    {
        User::factory()->create([
            'email' => 'dilson@gmail.com',
            'password' => Hash::make('senha-do-dilson'),
        ]);

        $response = $this->postJson($this->registerRoute, [
            'name' => 'Dilson',
            'email' => 'dilson@gmail.com',
            'password' => '12345678',
            'password_confirmation' => '12345678'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email'])
            ->assertJsonFragment([
                'message' => 'The email has already been taken.'
            ]);
    }

    public function test_user_is_registered_successfully_when_all_fields_are_provided()
    {
        $response = $this->post($this->registerRoute, [
            'name' => 'Dilson',
            'email' => 'dilson@gmail.com',
            'password' => '12345678',
            'password_confirmation' => '12345678'
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'dilson@gmail.com',
        ]);

        $response->assertStatus(201);
    }
    public function test_user_password_is_hashed_correctly_on_registration()
    {
        $password = '12345678';
        $this->postJson($this->registerRoute, [
            'name' => 'Dilson',
            'email' => 'dilson@gmail.com',
            'password' => $password,
            'password_confirmation' => $password
        ])->assertStatus(201);

        $user = User::where('email', 'dilson@gmail.com')->first();

        $this->assertNotEquals($password, $user->password);
        $this->assertTrue(Hash::check($password, $user->password));
    }

    public function test_user_registration_fails_when_name_is_less_than_five_characters()
    {
        $response = $this->postJson($this->registerRoute, [
            'name' => 'Dil',
            'email' => 'dilson@gmail.com',
            'password' => '12345678',
            'password_confirmation' => '12345678'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name'])
            ->assertJsonFragment([
                'message' => 'The name field must be at least 5 characters.'
            ]);
    }

    public function test_user_registration_fails_when_name_exceeds_maximum_length()
    {
        $response = $this->postJson($this->registerRoute, [
            'name' => Str::random(256),
            'email' => 'dilson@gmail.com',
            'password' => '12345678',
            'password_confirmation' => '12345678'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name'])
            ->assertJsonFragment([
                'message' => 'The name field must not be greater than 255 characters.'
            ]);
    }

    public function test_user_registration_fails_when_email_is_invalid()
    {
        $response = $this->postJson($this->registerRoute, [
            'name' => 'Dilson',
            'email' => 'dilsoncampelo',
            'password' => '12345678',
            'password_confirmation' => '12345678'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email'])
            ->assertJsonFragment([
                'message' => 'The email field must be a valid email address.'
            ]);
    }

    public function test_user_registration_fails_when_password_is_less_than_eight_characters()
    {
        $response = $this->postJson($this->registerRoute, [
            'name' => 'Dilson',
            'email' => 'dilson@gmail.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password'])
            ->assertJsonFragment([
                'message' => 'The password field must be at least 8 characters.'
            ]);
    }
}
