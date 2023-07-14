<?php

namespace Tests\Feature;

use Tests\TestCase;

use App\Models\Follow;
use App\Models\User;
use App\Models\Tweet; 

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class TweetFeatureTest extends TestCase
{
    Use RefreshDatabase; 
    use WithFaker;
        
    private User $user; 
    public function setUp(): void
    { 
        parent::setUp();
        $user = User::factory(1)->create();
        $this->user = User::find($user[0]->account_handle);
    }

    public function test_feature_success_create_tweet()
    {
        $this->assertDatabaseCount('tweets', 0);

        $tweet = Tweet::factory(1)->make();
        $tweet = $tweet->toArray();
        
        $response = $this->actingAs($this->user)->post("/api/tweet", $tweet[0]);

        $response->assertSessionHasNoErrors();
        $response->assertStatus(200);
        $this->assertDatabaseHas('tweets', [
            'user_account_handle'=> $this->user->account_handle,
            'text' => $tweet[0]['text'] 
        ]); 
        $this->assertDatabaseCount('tweets', 1);
    }

    public function test_feature_fail_create_tweet_without_text()
    {
        $this->assertDatabaseCount('tweets', 0);

        $tweet = Tweet::factory(1)->make([
            'text' => ''
        ]);
        $tweet = $tweet->toArray();
        
        $response = $this->actingAs($this->user)->post("/api/tweet", $tweet[0]);

        $response->assertSessionHasErrors('text');
        $response->assertStatus(302);
        $this->assertDatabaseMissing('tweets', [
            'user_account_handle'=> $this->user->account_handle,
            'text' => $tweet[0]['text'] 
        ]); 
        $this->assertDatabaseCount('tweets', 0);
    }

    public function test_feature_success_update_tweet()
    {
        $tweet = Tweet::factory(1)->create();
        $tweet = $tweet->toArray();
        $tweetId = $tweet[0]['id'];

        $this->assertDatabaseCount('tweets', 1);

        $updatedTweet = Tweet::factory(1)->make([
            'user_account_handle' => $this->user->account_handle, 
        ]);
        $updatedTweet = $updatedTweet->toArray();

        $response = $this->actingAs($this->user)->put("/api/tweet/$tweetId", $updatedTweet[0]);

        $response->assertSessionHasNoErrors();
        $response->assertStatus(200);
        $this->assertDatabaseHas('tweets', [
            'user_account_handle'=> $this->user->account_handle,
            'text' => $updatedTweet[0]['text'] 
        ]); 
        $this->assertDatabaseCount('tweets', 1);
    }

    public function test_feature_fail_update_tweet_does_not_exists()
    {
        $tweet = Tweet::factory(1)->create();
        $tweet = $tweet->toArray();
        $tweetId = $tweet[0]['id']+1;

        $this->assertDatabaseCount('tweets', 1);

        $updatedTweet = Tweet::factory(1)->make([
            'user_account_handle' => $this->user->account_handle, 
        ]);
        $updatedTweet = $updatedTweet->toArray();

        $response = $this->actingAs($this->user)->put("/api/tweet/$tweetId", $updatedTweet[0]);

        $response->assertStatus(404);
        $response->assertJsonStructure([
            'success', 
            'message', 
            'errors'
        ]);
        $this->assertDatabaseMissing('tweets', [
            'user_account_handle'=> $this->user->account_handle,
            'text' => $updatedTweet[0]['text'] 
        ]); 
        $this->assertDatabaseCount('tweets', 1);
    }

    public function test_feature_fail_update_tweet_does_not_own_tweet()
    {
        $newUser = User::factory(1)->create();
        $newUser = $newUser->toArray();

        $tweet = Tweet::factory(1)->create([
            'user_account_handle' => $newUser[0]['account_handle'], 
        ]);
        $tweet = $tweet->toArray();
        $tweetId = $tweet[0]['id'];

        $this->assertDatabaseCount('tweets', 1);

        $updatedTweet = Tweet::factory(1)->make([
            'user_account_handle' => $newUser[0]['account_handle'], 
        ]);
        $updatedTweet = $updatedTweet->toArray();

        $response = $this->actingAs($this->user)->put("/api/tweet/$tweetId", $updatedTweet[0]);

        $response->assertStatus(401);
        $response->assertJsonStructure([
            'success', 
            'message', 
            'errors'
        ]);
        $this->assertDatabaseMissing('tweets', [
            'user_account_handle'=> $this->user->account_handle,
            'text' => $updatedTweet[0]['text'] 
        ]); 
        $this->assertDatabaseCount('tweets', 1);
    }

    public function test_feature_success_delete_tweet()
    {
        $tweet = Tweet::factory(1)->create();
        $tweet = $tweet->toArray();
        $tweetId = $tweet[0]['id'];

        $this->assertDatabaseHas('tweets', [
            'user_account_handle'=> $this->user->account_handle,
            'text' => $tweet[0]['text'] 
        ]); 
        $this->assertDatabaseCount('tweets', 1);

        $response = $this->actingAs($this->user)->delete("/api/tweet/$tweetId");

        $response->assertSessionHasNoErrors();
        $response->assertStatus(200);
        $this->assertDatabaseMissing('tweets', [
            'user_account_handle'=> $this->user->account_handle,
            'text' => $tweet[0]['text'] 
        ]); 
        $this->assertDatabaseCount('tweets', 0);
    }

    public function test_feature_fail_delete_tweet_does_not_exists()
    {
        $tweet = Tweet::factory(1)->create();
        $tweet = $tweet->toArray();
        
        $this->assertDatabaseHas('tweets', [
            'user_account_handle'=> $this->user->account_handle,
            'text' => $tweet[0]['text'] 
        ]); 
        $this->assertDatabaseCount('tweets', 1);

        $tweetId = $tweet[0]['id']+1;

        $response = $this->actingAs($this->user)->delete("/api/tweet/$tweetId");

        $response->assertStatus(404);
        $response->assertJsonStructure([
            'success', 
            'message', 
            'errors'
        ]);
        $this->assertDatabaseHas('tweets', [
            'user_account_handle'=> $this->user->account_handle,
            'text' => $tweet[0]['text'] 
        ]); 
        $this->assertDatabaseCount('tweets', 1);
    }

    public function test_feature_fail_delete_tweet_does_not_own_tweet()
    {
        $newUser = User::factory(1)->create();
        $newUser = $newUser->toArray();

        $tweet = Tweet::factory(1)->create([
            'user_account_handle' => $newUser[0]['account_handle'], 
        ]);
        $tweet = $tweet->toArray();
        $tweetId = $tweet[0]['id'];

        $this->assertDatabaseHas('tweets', [
            'user_account_handle'=> $newUser[0]['account_handle'],
            'text' => $tweet[0]['text'] 
        ]); 
        $this->assertDatabaseCount('tweets', 1);

        $response = $this->actingAs($this->user)->delete("/api/tweet/$tweetId");

        $response->assertStatus(401);
        $response->assertJsonStructure([
            'success', 
            'message', 
            'errors'
        ]);
        $this->assertDatabaseHas('tweets', [
            'user_account_handle'=> $newUser[0]['account_handle'],
            'text' => $tweet[0]['text'] 
        ]); 
        $this->assertDatabaseCount('tweets', 1);
    }

    public function test_feature_success_get_all_tweets_own()
    {
        $count = 3;
        $tweets = Tweet::factory($count)->create();

        $this->assertDatabaseCount('tweets', $count);

        $accountHandle = $this->user->account_handle;
        $response = $this->actingAs($this->user)->get("/api/$accountHandle/tweets");

        $response->assertStatus(200);
    }

    public function test_feature_success_get_all_tweets_following()
    {
        $newUser = User::factory(1)->create();
        $newUser = $newUser->toArray();

        $this->assertDatabaseCount('users', 2);

        $count = 3;
        $tweets = Tweet::factory($count)->create([
            'user_account_handle' => $newUser[0]['account_handle']
        ]);
        $this->assertDatabaseCount('tweets', $count);

        $followingRelationship = Follow::factory(1)->create([
            'follower_id' => $this->user->account_handle,
            'following_id' => $newUser[0]['account_handle']
        ]);

        $this->assertDatabaseHas('follows', [
            'follower_id' => $this->user->account_handle,
            'following_id' => $newUser[0]['account_handle']
        ]); 
        $this->assertDatabaseCount('follows', 1);

        $accountHandle = $newUser[0]['account_handle'];
        $response = $this->actingAs($this->user)->get("/api/$accountHandle/tweets");

        $response->assertStatus(200);
    }

    public function test_feature_fail_get_all_tweets_not_following()
    {
        $newUser = User::factory(1)->create();
        $newUser = $newUser->toArray();

        $this->assertDatabaseCount('users', 2);

        $count = 3;
        $tweets = Tweet::factory($count)->create([
            'user_account_handle' => $newUser[0]['account_handle']
        ]);
        $this->assertDatabaseCount('tweets', $count);

        $this->assertDatabaseMissing('follows', [
            'follower_id' => $this->user->account_handle,
            'following_id' => $newUser[0]['account_handle']
        ]); 
        $this->assertDatabaseCount('follows', 0);

        $accountHandle = $newUser[0]['account_handle'];
        $response = $this->actingAs($this->user)->get("/api/$accountHandle/tweets");

        $response->assertStatus(401);
    }
}
