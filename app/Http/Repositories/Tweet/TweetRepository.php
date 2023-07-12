<?php

namespace App\Http\Repositories\Tweet; 

use App\Exceptions\UserDoesNotOwnTweetException;
use App\Http\Repositories\Follow\FollowRepository;
use App\Models\Attachment;
use App\Models\User; 
use App\Models\Tweet; 

use Illuminate\Http\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class TweetRepository implements TweetRepositoryInterface
{
    public function createTweet(array $data)
    {
        $user = Auth::user();

        $tweet = [
            'user_account_handle' => $user->account_handle, 
            'text' => $data['text']
        ];
        $tweet = Tweet::create($tweet);

        if(array_key_exists('attachment', $data)) {
           $this->saveAttachment($data['attachment'], $tweet->id);
        }

        $attachment = $tweet->attachments;
        return $tweet;
    }
    
    public function deleteTweet(String $tweetId)
    {
        $tweet = Tweet::findOrFail($tweetId);

        $this->userOwnsTweet($tweetId);

        $tweet->delete();
    }

    public function getTweetsOfUser(String $accountHandle)
    {
        $user = User::findOrFail($accountHandle);

        $followRepo = new FollowRepository();
        $followRepo->isFollowing($accountHandle);

        $tweets = $user->tweets; 
        foreach($tweets as $tweet) {
            $attachments = $tweet->attachments; 
        }

        return $tweets; 
    }

    private function saveAttachment(array $attachments, int $tweetId) 
    {
        foreach($attachments as $attachment) {
            $name = $tweetId . '_' . $attachment->getClientOriginalName();

            $toSaveAttachment = [
                'tweet_id' => $tweetId,
                'attachment' => $name
            ]; 
            Attachment::create($toSaveAttachment);

            $path = Storage::putFileAs('attachments', $attachment, $name);
        }
    }

    private function userOwnsTweet(String $tweetId)
    {
        $user = Auth::user();

        If(!$user->tweets()->find($tweetId)) {
            throw new UserDoesNotOwnTweetException;
        }
    }
} 