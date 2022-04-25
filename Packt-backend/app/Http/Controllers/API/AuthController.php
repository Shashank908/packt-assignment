<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Exception;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $validated = Validator::make($request->all(),[
                'email' => 'required|email',
                'password' => 'required',
                'device_name' => 'required',
            ]);

            if ($validated->fails()) {
                return response([
                    'status' => 401,
                    'message' => $validated ->messages()
                ]);
            }
    
            $user = User::where('email', $request->email)->first();
         
            if (! $user || ! Hash::check($request->password, $user->password)) {
                return response([
                    'status' => 401,
                    'message' => "Please check your credentials"
                ]);
            }
         
            return response([
                'accessToken' => $user->createToken($request->device_name)->plainTextToken,
                'user' => $user,
                'status' => 200
            ]);
        } catch (Exception $ex) {
            return response([
                'status' => 500,
                'message' => $ex->getMessage()
            ]);
        }
    }

    public function register(Request $request)
    {
        try {
            $validated = Validator::make($request->all(),[
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required'
            ]);
            
            if ($validated->fails()) {
                return response([
                    'status' => 401,
                    'message' => $validated->messages()
                ]);
            }
    
            $user = User::create([
                'name' => $request['name'],
                'password' => bcrypt($request['password']),
                'email' => $request['email']
            ]);
            
            return response([
                'accessToken' => $user->createToken('tokens')->plainTextToken,
                'user' => $user,
                'status' => 200
            ]);
        } catch (Exception $th) {
            return response([
                'status' => 500,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function logout(Request $request)
    {
        $user = $request->user()->currentAccessToken()->delete();
        if ($user) 
        {
            return response()->json([
                'status' => 200,
                'message' => 'Successfully Logged Out!'
            ]);
        }
    }
}
