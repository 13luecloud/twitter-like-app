<?php

namespace App\Http\Repositories\Tweet; 

use App\Http\Repositories\Follow\FollowRepository;
use App\Models\User; 
use App\Models\Tweet; 

class TweetRepository implements TweetRepositoryInterface
{
    public function createTweet(array $data)
    {
        $user = Auth::user();
    }

    public function getTweetsOfUser(String $accountHandle)
    {
        $user = User::findOrFail($accountHandle);

        $followRepo = new FollowRepository();
        $followRepo->isFollowing($accountHandle);

        return $user->tweets;
    }

    private function saveMedia(array $attachments) 
    {
        foreach($attachments as $attachment) {
          
        }
    }
} 