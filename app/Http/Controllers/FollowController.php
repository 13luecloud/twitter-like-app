<?php

namespace App\Http\Controllers;

use App\Http\Repositories\Follow\FollowRepositoryInterface; 
use App\Http\Requests\UserAccountHandleRequest; 

use Illuminate\Http\Request;

class FollowController extends Controller
{
    private $repository; 
    public function __construct(FollowRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function followUser(UserAccountHandleRequest $request)
    {
        return response()->success(
            'Successfully followed user',
            $this->repository->followUser($request->validated()),
        );
    }

    public function unfollowUser(UserAccountHandleRequest $request)
    {
        return response()->success(
            'Successfully unfollowed user',
            $this->repository->unfollowUser($request->validated()),
        );
    }
}
