<?php

namespace App\Http\Repositories\Follow;

interface FollowRepositoryInterface
{
    public function followUser(array $data, String $userAccountHandle); 
    public function unfollowUser(array $data, String $userAccountHandle);
}