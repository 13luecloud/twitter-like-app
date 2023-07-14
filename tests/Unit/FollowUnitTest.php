<?php

namespace Tests\Unit;

use Tests\TestCase;

use App\Http\Repositories\Follow\FollowRepository; 
use App\Models\User;
use App\Models\Follow; 

use Illuminate\Foundation\Testing\RefreshDatabase;

class FollowUnitTest extends TestCase
{
    use RefreshDatabase; 

    private $repository;
    public function setUp(): void 
    {
        parent::setUp();
        $this->repository = app(FollowRepository::class);
    }

    public function test_unit_success_follow_user(): void
    {
        $userFollower = User::factory(1)->create();
        $userFollower = $userFollower->toArray();
        $userFollowing = User::factory(1)->create();
        $userFollowing = $userFollowing->toArray();

        $this->assertDatabaseCount('users', 2);
        $this->assertDatabaseCount('follows', 0);

        $updatedFollowing = $this->repository->followUser($userFollowing[0], $userFollower[0]['account_handle']);
        $this->assertDatabaseHas('follows', [
            'follower_id' => $userFollower[0]['account_handle'], 
            'following_id' => $userFollowing[0]['account_handle']
        ]);

        $this->assertDatabaseCount('follows', 1);
    }

    public function test_unit_success_unfollow_user(): void
    {
        $userFollower = User::factory(1)->create();
        $userFollower = $userFollower->toArray();
        $userFollowing = User::factory(1)->create();
        $userFollowing = $userFollowing->toArray();

        $followRelationship = Follow::factory(1)->create([
            'follower_id' => $userFollower[0]['account_handle'], 
            'following_id' => $userFollowing[0]['account_handle']
        ]);

        $this->assertDatabaseCount('users', 2);
        $this->assertDatabaseCount('follows', 1);

        $updatedFollowing = $this->repository->unfollowUser($userFollowing[0], $userFollower[0]['account_handle']);
        $this->assertDatabaseMissing('follows', [
            'follower_id' => $userFollower[0]['account_handle'], 
            'following_id' => $userFollowing[0]['account_handle']
        ]);

        $this->assertDatabaseCount('follows', 0);
    }
}
