<?php

namespace App\Http\Repositories\Follow;

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
    }

    public function unfollowUser(array $data)
    {
        $user = Auth::user(); 

        $followingRelationship = Follow::where([
            ['follower_id', $user->account_handle], 
            ['following_id', $data['account_handle']]
        ])->first(); 

        $followingRelationship->delete();
    }
}