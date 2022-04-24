<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    protected function setUp(): void 
    {
        parent::setUp();
        // set your headers here
        $user = User::factory()->create([
            'password' => Hash::make($password = '123456'),
        ]);

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => $password,
            'device_name' => 'test'
        ]);

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $response['accessToken'],
            'Accept' => 'application/json'
        ]);
    }

    public function test_user_all_api_status_test()
    {
        $response = $this->get('/api/users/all');
        $response->assertJson(['status' => 200]);
        $this->assertArrayHasKey('message', $response->json());
        $this->assertArrayHasKey('status', $response->json());
    }

    public function test_all_post_api_status_test()
    {
        $response = $this->get('/api/posts/all');
        $response->assertJson(['status' => 200]);
        $this->assertArrayHasKey('message', $response->json());
        $this->assertArrayHasKey('status', $response->json());
    }   
    
    public function test_delete_user_api_test()
    {
        $response = $this->post('/api/register', [
            'email' => rand(10000,99999).'@laravel.com',
            'password' => Hash::make($password = '123456'),
            'name' => rand(10000,99999)
        ]);
        $user_data = $response->json();
        $response = $this->delete('/api/users/' . $user_data['user']['id']);
        $response->assertJson(['status' => 200]);
        $this->assertArrayHasKey('message', $response->json());
        $this->assertArrayHasKey('status', $response->json());
    }

    // public function test_post_content_api_test()
    // {
    //     $post_data = Post::factory()->create();
    //     //$post_data = $post_data->toArray();
    //     $a = [
    //         'title' => $post_data->post_title,
    //         'postBody' => $post_data->post_body
    //     ];
    //     $response = $this->post('/api/posts',[ $a]);
    //     $response->assertStatus(200);
    // }

    public function test_delete_posts_api_test()
    {
        $post_data = Post::factory()->create();
        $response = $this->delete('/api/delete/posts/' . $post_data->id);
        $response->assertJson(['status' => 200]);
    }

    public function test_edit_post_api_test()
    {
        $post_data = Post::factory()->create();
        $response = $this->put('/api/posts/' . $post_data->id, [
            'title' => "change in title",
            'postBody' => "change in body"
        ]);
        $response->assertJson(['status' => 200]);
    }
}
