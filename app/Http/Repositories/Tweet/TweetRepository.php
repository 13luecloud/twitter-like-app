<?php

namespace App\Http\Repositories\Tweet; 

use App\Http\Repositories\Follow\FollowRepository;
use App\Models\Attachment;
use App\Models\User; 
use App\Models\Tweet; 

use Illuminate\Http\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

        if(sizeOf($data['attachment']) > 0) {
            $attachments = $this->saveAttachment($data['attachment'], $tweet->id);
            
            return [$tweet, $attachments];        
        }

        return $tweet;
    }

    public function getTweetsOfUser(String $accountHandle)
    {
        $user = User::findOrFail($accountHandle);

        $followRepo = new FollowRepository();
        $followRepo->isFollowing($accountHandle);

        return $user->tweets;
    }

    private function saveAttachment(array $attachments, int $tweetId) 
    {
        $savedAttachments = [];
        foreach($attachments as $attachment) {
            $name = $tweetId . '_' . $attachment->getClientOriginalName();

            $toSaveAttachment = [
                'tweet_id' => $tweetId,
                'attachment' => $name
            ]; 
            Attachment::create($toSaveAttachment);

            $path = Storage::putFileAs('attachments', $attachment, $name);
            array_push($savedAttachments, $path);
        }

        return $savedAttachments;
    }
} 