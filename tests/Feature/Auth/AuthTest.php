<?php

namespace Tests\e2e;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use DatabaseTransactions;

    public function testSignup()
    {
        $this->json('post', '/api/register', [
            "name" => "testUser",
            "email" => "testUser@gmail.com",
            "password" => "testPass",
            "password_confirmation" => "testPass"
        ])->assertStatus(201);

        $this->assertDatabaseHas('users', ['name' => "testUser"]);

    }


    public function testLogin()
    {
        $user = factory(User::class)->create(['password' => bcrypt('testPass')]);

        $response = $this->json('post', '/api/auth', [
            "email" => $user->email,
            "password" => 'testPass',
        ]);

        $response->assertStatus(200);
    }

}
