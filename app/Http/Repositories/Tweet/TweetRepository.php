<?php

namespace App\Http\Repositories\Tweet; 

use App\Http\Repositories\Follow\FollowRepository;
use App\Models\User; 
use App\Models\Tweet; 

class TweetRepository implements TweetRepositoryInterface
{
    public function getTweetsOfUser(String $accountHandle)
    {
        User::findOrFail($accountHandle);

        $followRepo = new FollowRepository();
        $followRepo->isFollowing($accountHandle);

        return Tweet::where('user_account_handle', $accountHandle)->get();
    }
}