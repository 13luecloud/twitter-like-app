<?php

namespace Tests\Unit;

use Tests\TestCase;

use App\Http\Repositories\Follow\FollowRepository; 
use App\Models\User;

use Illuminate\Foundation\Testing\RefreshDatabase;

class FollowUnitTest extends TestCase
{
    use RefreshDatabase; 

    private $repository;
    private User $loggedInUser; 
    public function setUp(): void 
    {
        parent::setUp();
        $this->repository = app(FollowRepository::class);

        $user = User::factory(1)->create();
        $this->loggedInUser = $user; 
    }

    public function test_unit_success_follow_user(): void
    {
        $this->actingAs($this->loggedInUser);
    }
}
