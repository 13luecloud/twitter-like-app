<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Exceptions\PasswordMismatchException; 
use App\Http\Repositories\User\UserRepository; 

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;

class UserUnitTest extends TestCase
{
    use RefreshDatabase; 
    use WithFaker;

    private $repository;
    public function setUp(): void 
    {
        parent::setUp();
        $this->repository = app(UserRepository::class);
    }

    public function test_unit_success_create_new_users()
    {
        $this->assertDatabaseCount('users', 0);

        $count = 5;
        $userAccounts = $this->user_account($count);

        foreach($userAccounts as $account) {
            $createdAccount = $this->repository->createUser($account);

            $this->assertDatabaseHas('users', [
                'account_handle' => $account['account_handle'], 
                'display_name' => $account['display_name'], 
                'biography' => $account['biography'],
                'email' => $account['email']
            ]);
            $this->assertEquals($createdAccount->account_handle, $account['account_handle']);
            $this->assertEquals($createdAccount->display_name, $account['display_name']);
            $this->assertEquals($createdAccount->biography, $account['biography']);
            $this->assertEquals($createdAccount->email, $account['email']);
            $this->assertTrue(Hash::check($account['password'], $createdAccount->password));
        }

        $this->assertDatabaseCount('users', $count);
    }

    public function test_unit_fail_create_new_users_passwords_mismatch()
    {
        $this->assertDatabaseCount('users', 0);

        $count = 1;
        $userAccounts = $this->user_account($count);

        foreach($userAccounts as $account) {
            $account['confirm_password'] = 'something else entirely';

            $this->expectException(PasswordMismatchException::class);
            $createdAccount = $this->repository->createUser($account);

            $this->assertDatabaseMissing('users', [
                'account_handle' => $account['account_handle'], 
                'display_name' => $account['display_name'], 
                'biography' => $account['biography'],
                'email' => $account['email']
            ]);
        }

        $this->assertDatabaseCount('users', 0);
    }

    private function user_account(int $count) 
    {
        $userAccounts = []; 
        for($i = 0; $i < $count; $i++) {
            $name = $this->faker->name();
            $password = 'password';

            $account = [
                'account_handle' => str_replace(' ', '', $name),
                'display_name' => $name, 
                'biography' => $this->faker->sentence, 
                'email' => $this->faker->unique()->safeEmail(),
                'password' => $password,
                'confirm_password' => $password
            ];

            array_push($userAccounts, $account);
        }
    
        return $userAccounts;
    }
}
