<?php

namespace App\Http\Controllers;

use App\Http\Requests\TweetCreateUpdateRequest; 
use App\Http\Repositories\Tweet\TweetRepositoryInterface; 

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

    public function store(TweetCreateUpdateRequest $request)
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

    public function update(TweetCreateUpdateRequest $request, string $id)
    {
        return response()->success(
            'Successfully updated tweet',
            $this->repository->updateTweet($request->validated(), $id)
        );
    }

    public function destroy(string $id)
    {
        return response()->success(
            'Successfully deleted tweet',
            $this->repository->deleteTweet($id)
        );
    }
}
