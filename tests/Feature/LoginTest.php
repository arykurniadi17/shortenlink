<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRequiresEmailAndLogin()
    {
        $response = $this->json('POST', 'api/login');
        $response->assertStatus(422)
            ->assertJson([
                'response' => [
                    'code' => 422,
                    'message' => [
                        'email' => ['The email field is required.'],
                        'password' => ['The password field is required.'],            
                    ]
                ],
            ]);        
    }

    public function testUserLoginsSuccessfully()
    {
        $params = ['email' => 'admin@gmail.com', 'password' => 'admin123'];
        $response = $this->json('POST', 'api/login', $params);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'response' => [
                    'code',
                    'message'
                ],
                'data' => [
                    'id',
                    'name',
                    'email',
                    'email_verified_at',
                    'created_at',
                    'updated_at',
                    'api_token',
                ],
            ]);        
    }
}
