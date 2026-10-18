<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{

    public function test_user_can_login(): void
    {
        $user= [
        'email' => 'test@example.com',
        'password' => 'password'
        ];

        $response = $this->post('/api/login', $user);

        $response->assertStatus(200);
    }
}
