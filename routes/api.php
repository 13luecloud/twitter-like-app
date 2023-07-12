<?php

use App\Http\Controllers\FollowController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TweetController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::controller(UserController::class)->group(function() {
    Route::post('/register', 'store');
    Route::post('/login', 'loginUser');
});

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::controller(FollowController::class)->group(function() {
        Route::post('/follow', 'followUser');
        Route::delete('/unfollow', 'unfollowUser');
    });

    Route::controller(TweetController::class)->group(function() {
        Route::get('/{account_handle}/tweets', 'index');
    });

    Route::post('/logout', [UserController::class, 'logoutUser']);
});
