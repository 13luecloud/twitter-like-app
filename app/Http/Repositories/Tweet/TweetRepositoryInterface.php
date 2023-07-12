<?php

namespace App\Http\Repositories\Tweet; 

interface TweetRepositoryInterface 
{
    public function getTweetsOfUser(String $accountHandle);
}