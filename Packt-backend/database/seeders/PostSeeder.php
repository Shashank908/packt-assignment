<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arr = [];
        $user_response = Http::get('https://gorest.co.in/public/v2/users');
        if($user_response->successful())
        {
            $response = $user_response->json();
            foreach ($response as $res_key => $res_value) 
            {
                $this->prepareUserData($res_value, $arr);
            }
        } elseif ($response->failed() || $response->clientError() || $response->serverError()) {
            dump('Something went wrong fetching Post Data!');
        }

        $post_response = Http::get('https://gorest.co.in/public/v2/posts');
        $post_data = $this->checkResults($post_response, $arr);
    }

    public function checkResults($response, $arr)
    {
        if($response->successful())
        {
            $response = $response->json();
            $key = array_key_first($arr);
            foreach ($response as $res_key => $res_value) 
            {
                $this->preparePostData($res_value, $arr[$key]);
                $key++;
            }
        } elseif ($response->failed() || $response->clientError() || $response->serverError()) {
            dump('Something went wrong fetching Post Data!');
        }
        return $arr;
    }

    public function preparePostData($res_value, $val)
    {
        if(!empty($res_value) && is_array($res_value)
            && (isset($res_value['title']) && isset($res_value['body'])))
        {
            $arr = array (
                'post_title' => $res_value['title'],
                'post_body'=> $res_value['body'],
                'user_id' => $val
            );
            Post::create($arr);
        }
    }

    public function prepareUserData($res_value, &$arr)
    {
        if(!empty($res_value) && is_array($res_value)
            && (isset($res_value['name']) && isset($res_value['email'])))
        {
            if(!User::where('email', $res_value['email'])->exists())
            {
                $arr1 = array (
                    'name' => $res_value['name'],
                    'email'=> $res_value['email'],
                    'password' => bcrypt(random_int(100000, 999999)),
                    'gender' => isset($res_value['gender']) ? $res_value['gender'] : '',
                    'status' => isset($res_value['status']) ? $res_value['status'] : ''
                );
                $data = User::create($arr1);
                $data = $data->toArray();
                $arr[] = $data['id'];
            }
        }
    }
}