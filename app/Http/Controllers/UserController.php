<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\UserCreateRequest;
use App\Http\Repositories\User\UserRepositoryInterface; 

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private $repository; 
    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function loginUser(LoginUserRequest $request)
    {
        return response()->success('Successfully logged in user', $this->repository->loginUser($request->validated()));        
    }

    public function logoutUser()
    {
        Auth::user()->currentAccessToken()->delete();
        
        return response()->success('Successfully logged out user');
    }

    public function index()
    {
        //
    }

    public function store(UserCreateRequest $request)
    {
        return response()->success(
            'Successfully created user', 
            $this->repository->createUser($request->validated()),
        );
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
