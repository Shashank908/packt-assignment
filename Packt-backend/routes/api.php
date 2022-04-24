<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->post('/profile', [App\Http\Controllers\API\UserController::class, 'updateProfile']);
Route::middleware('auth:sanctum')->get('/users/all', [App\Http\Controllers\API\UserController::class, 'allUsers']);
Route::middleware('auth:sanctum')->post('/posts', [App\Http\Controllers\API\UserController::class, 'post']);
Route::middleware('auth:sanctum')->get('/posts/all', [App\Http\Controllers\API\UserController::class, 'allPosts']);
Route::middleware('auth:sanctum')->delete('/delete/posts/{id}', [App\Http\Controllers\API\UserController::class, 'deletePost']);
Route::middleware('auth:sanctum')->put('/posts/{id}', [App\Http\Controllers\API\UserController::class, 'editPost']);
Route::middleware('auth:sanctum')->match(['put', 'post'], '/users', [App\Http\Controllers\API\UserController::class, 'editOrAddUser']);
Route::middleware('auth:sanctum')->delete('/users/{id}', [App\Http\Controllers\API\UserController::class, 'deleteUser']);


Route::post('/login', [App\Http\Controllers\API\AuthController::class, 'login']);
Route::post('/register', [App\Http\Controllers\API\AuthController::class, 'register']);
Route::middleware('auth:sanctum')->get('/logout', [App\Http\Controllers\API\AuthController::class, 'logout']);

// Fallback path, in case not matches with above API's
Route::fallback(function(){
    return response([
        'status' => 404,
        'message' => 'Resource not found!.'
    ]);
})->name('api.fallback.404');