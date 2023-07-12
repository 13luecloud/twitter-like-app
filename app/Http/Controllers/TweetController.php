<?php

namespace App\Http\Controllers;

use App\Http\Requests\TweetCreateRequest; 
use App\Http\Repositories\Tweet\TweetRepositoryInterface; 

use Illuminate\Http\Request;

class TweetController extends Controller
{
    private $repository; 
    public function __construct(TweetRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index(String $account_handle)
    {
        return response()->success(
            "Successfully fetched all of user's tweets", 
            $this->repository->getTweetsOfUser($account_handle)
        );
    }

    public function store(TweetCreateRequest $request)
    {
        return response()->success(
            'Successfully created tweet',
            $this->repository->createTweet($request->validated())
        );
    }

    public function show(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
