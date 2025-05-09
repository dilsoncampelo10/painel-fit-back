<?php

namespace Tests\Feature\app\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_access_and_get_own_data()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson(self::api('profile'));

        $response->assertStatus(200)
            ->assertJson([
                'id' => $user->id,
                "name" => $user->name,
                "email" => $user->email,
            ]);
    }

    public function test_unauthenticated_user_cannot_access_index()
    {
        $response = $this->getJson(self::api('profile'));

        $response->assertStatus(401);
    }
}
