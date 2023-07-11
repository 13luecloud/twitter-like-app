<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Follow;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();
        Follow::factory(10)->create();
    }
}
