<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_register_test()
    {
        $response = $this->post('/api/register', [
            'email' => rand(10000,99999).'@laravel.com',
            'password' => Hash::make($password = '123456'),
            'name' => rand(10000,99999)
        ]);

        $response->assertStatus(200);
        $this->assertArrayHasKey('accessToken', $response->json());
        $this->assertArrayHasKey('user', $response->json());
    }

    public function test_user_register_not_with_properData_test()
    {
        $response = $this->post('/api/register', [
            'email' => rand(10000,99999).'@laravel.com',
            'name' => rand(10000,99999)
        ]);

        $response->assertJson(['status' => 401]);
        $this->assertArrayHasKey('message', $response->json());
        $this->assertArrayHasKey('status', $response->json());
    }

    public function test_login_test()
    {
        $user = User::factory()->create([
            'password' => Hash::make($password = '123456'),
        ]);

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => $password,
            'device_name' => 'test'
        ]);

        $response->assertStatus(200);
        $this->assertArrayHasKey('accessToken', $response->json());
        $this->assertArrayHasKey('user', $response->json());
    }

    public function test_login_without_token_test()
    {
        $user = User::factory()->create();

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => $user->password,
            'device_name' => 'test'
        ]);
        $response->assertStatus(200);
        $response->assertJson(['status' => 401]);
        $this->assertArrayHasKey('message', $response->json());
        $this->assertArrayHasKey('status', $response->json());
    }

    public function test_logout_test()
    {
        $user = User::factory()->create([
            'password' => Hash::make($password = '123456'),
        ]);

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => $password,
            'device_name' => 'test'
        ])->json();
        
        $response1 = $this->withHeader('Authorization', 'Bearer ' . $response['accessToken'])
            ->json('get', '/api/logout');
        $response1->assertStatus(200);
        $this->assertArrayHasKey('message', $response1->json());
        $this->assertArrayHasKey('status', $response1->json());
    }
}
