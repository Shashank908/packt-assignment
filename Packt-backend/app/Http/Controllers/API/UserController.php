<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\User;
use App\Models\Post;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function updateProfile(Request $request)
    {
        try {
            $data = $request->getContent();
            $data = json_decode($data, true);
            $email = isset($data['email']) ? $data['email'] : '';

            if(!empty($email) && User::where('email', $email)->exists())
            {
                $user = User::where('email', $email)
                    ->update($data);
            }
            
            return response([
                'status' => 200,
                'data' => $data,
                'message' => ''
            ]);
        } catch (Exception $ex) {
            return response([
                'status' => 500,
                'data' => $data,
                'message' => $ex->getMessage()
            ]);
        }
    }

    public function allUsers()
    {
        try {
            $user_data = User::select('address', 'city', 'country','email', 'gender', 'id', 'name', 'status')->get();
            if(!empty($user_data))
            {
                $user_data = $user_data->toArray();
            }
            return response([
                'status' => 200,
                'message' => '',
                'data' => $user_data
            ]);
        } catch (Exception $ex) {
            return response([
                'status' => 500,
                'message' => $ex->getMessage(),
                'data' => ''
            ]);
        }
    }

    public function post(Request $request)
    {
        try {

            $data = $request->getContent();
            $data = json_decode($data, true);

            $validated = Validator::make($data,[
                'title' => 'required',
                'postBody' => 'required'
            ]);
            if ($validated->fails()) {
                return response([
                    'status' => 400,
                    'message' => $validated ->messages()
                ]);
            }
    
            $user_data = $request->user()->toArray();
            $user_data = isset($user_data['id']) ? $user_data['id'] : '';
    
            $post = Post::create([
                'post_body' => $data['postBody'],
                'post_title' => $data['title'],
                'user_id' => $user_data
            ]);
            return response([
                'status' => 200,
                'post' => $post,
                'user' => $request->user()
            ]);
        } catch (Exception $ex) {
            return response([
                'status' => 500,
                'message' => $ex->getMessage()
            ]);
        }
    }

    public function editPost(Request $request, $id)
    {
        try {
            $data = $request->getContent();
            $data = json_decode($data, true);

            $validated = Validator::make($data, [
                'title' => 'required',
                'postBody' => 'required',
            ]);
            
            if ($validated->fails()) {
                return response([
                    'status' => 400,
                    'message' => $validated ->messages()
                ]);
            }
            $user_data = $request->user()->toArray();
            $user_id = isset($user_data['id']) ? $user_data['id'] : '';

            Post::where('id', $id)->update([
                'post_body' => $data['postBody'],
                'post_title' => $data['title'],
                'user_id' => $user_id
            ]);

            return response([
                'status' => 200,
                'data' => '',
                'message' => ''
            ]);
        } catch (Exception $ex) {
            return response([
                'status' => 500,
                'data' => $data,
                'message' => $ex->getMessage()
            ]);
        }
    }

    public function allPosts()
    {
        try {
            $posts = Post::with('user:id,name,email')->get();
            if(!empty($posts))
            {
                $posts->toArray();
            }
            
            return response([
                'status' => 200,
                'data' => $posts,
                'message' => ''
            ]);
        } catch (Exception $ex) {
            return response([
                'status' => 500,
                'data' => '',
                'message' => $ex->getMessage()
            ]);
        }
    }

    public function deletePost($id)
    {
        try {
            $query = Post::where('id', $id);
            if($query->exists())
            {
                $query->delete();
            }
            return response([
                'status' => 200,
                'data' => '',
                'message' => ''
            ]);
        } catch (Exception $ex) {
            return response([
                'status' => 500,
                'data' => '',
                'message' => $ex->getMessage()
            ]);
        }
    } 

    public function editOrAddUser(Request $request)
    {
        try {
            $method = $request->method();
            $data = $request->getContent();
            $data = json_decode($data, true);
            $valid_field = array('email' => 'required|email',);
            if (strtolower($method) === 'post') 
            {
                $valid_field['password'] = 'required';
            } 
            $validated = Validator::make($data, $valid_field);
            if ($validated->fails()) {
                return response([
                    'status' => 401,
                    'message' => $validated ->messages()
                ]);
            }
            $query = User::where('email', $data['email']);
            if(isset($data['password']))
            {
                $data['password'] = bcrypt($data['password']);
            }
            
            if(array_key_exists('dataFlag', $data))
            {
                unset($data['dataFlag']);
            }
            if($query->exists())
            {
                $query->update($data);
            } else {
                $query->insert($data);
            }
            return response([
                'status' => 200,
                'data' => '',
                'message' => ''
            ]);
        } catch (Exception $ex) {
            return response([
                'status' => 500,
                'data' => '',
                'message' => $ex->getMessage()
            ]);
        }
    }

    public function deleteUser($id)
    {
        try {
            $query = User::where('id', $id);
            if($query->exists())
            {
                $query->delete();
            }
            return response([
                'status' => 200,
                'data' => '',
                'message' => ''
            ]);
        } catch (Exception $ex) {
            return response([
                'status' => 500,
                'data' => '',
                'message' => $ex->getMessage()
            ]);
        }
    }
}