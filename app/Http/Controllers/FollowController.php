<?php

namespace App\Http\Controllers;

use App\Http\Repositories\User\UserRepositoryInterface; 

use Illuminate\Http\Request;

class FollowController extends Controller
{
    private $repository; 
    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
}
