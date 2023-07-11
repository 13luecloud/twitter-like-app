<?php

namespace App\Http\Repositories\Follow;

interface FollowRepositoryInterface
{
    public function followUser(array $data); 
    public function unfollowUser(array $data);
}