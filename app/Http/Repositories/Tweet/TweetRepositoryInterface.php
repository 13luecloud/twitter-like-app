<?php

namespace App\Http\Repositories\Tweet; 

interface TweetRepositoryInterface 
{
    public function createTweet(array $data);
    public function getTweetsOfUser(String $accountHandle);
}