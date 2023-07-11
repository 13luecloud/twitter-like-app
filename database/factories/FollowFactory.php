<?php

namespace Database\Factories;

use App\Models\User;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Follow>
 */
class FollowFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $follows = $this->getFollows();

        return [
            'follower_id' => $follows[0], 
            'following_id' => $follows[1],
        ];
    }

    private function getFollows()
    {
        $follower = User::inRandomOrder()->first()->account_handle; 
        $following = User::inRandomOrder()->first()->account_handle;

        while($follower === $following) {
            $following = User::inRandomOrder()->first()->account_handle;
        }

        return [$follower, $following];
    }
}
