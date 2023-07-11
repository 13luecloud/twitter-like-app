<?php

namespace App\Providers;

use App\Http\Repositories\User\UserRepository; 
use App\Http\Repositories\User\UserRepositoryInterface; 
use App\Http\Repositories\Follow\FollowRepository; 
use App\Http\Repositories\Follow\FollowRepositoryInterface; 

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(FollowRepositoryInterface::class, FollowRepository::class);
    }
}
