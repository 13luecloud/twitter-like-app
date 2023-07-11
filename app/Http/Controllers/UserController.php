<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Repositories\User\UserRepositoryInterface; 

use Illuminate\Http\Request;

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

    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        //
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
