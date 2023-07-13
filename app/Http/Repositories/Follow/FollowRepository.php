<?php

namespace App\Http\Repositories\Follow;

use App\Exceptions\NotFollowingException;
use App\Models\Follow; 

use Illuminate\Support\Facades\Auth;

class FollowRepository implements FollowRepositoryInterface
{
    public function followUser(array $data) 
    {
        $user = Auth::user(); 

        $followRelationship = [
            'follower_id'   => $user->account_handle,
            'following_id'  => $data['account_handle']
        ];

        Follow::create($followRelationship);

        return $this->getUpdatedFollowing($user->account_handle, $data['account_handle']);
    }

    public function unfollowUser(array $data)
    {
        $user = Auth::user(); 

        $followingRelationship = Follow::where([
            ['follower_id', $user->account_handle], 
            ['following_id', $data['account_handle']]
        ])->first(); 

        $followingRelationship->delete();
        
        return $this->getUpdatedFollowing($user->account_handle, $data['account_handle']);
    }

    public function isFollowing(String $targetAccountHandle)
    {
        $user = Auth::user();

        if($user->account_handle !== $targetAccountHandle) {
            $isFollowing = Follow::where([
                ['follower_id', $user->account_handle], 
                ['following_id', $targetAccountHandle]
            ])->first();
    
            if(!$isFollowing) {
                throw new NotFollowingException;
            }
        }
    }

    private function getUpdatedFollowing(String $userAccountHandle, String $targetAccountHandle)
    {
        $userFollowing = Follow::where('follower_id', $userAccountHandle)->get();
        $targetFollowers = Follow::where('following_id', $targetAccountHandle)->get();

        return [
            'user_following' => $userFollowing, 
            'target_followers' => $targetFollowers,
        ];
    }
}