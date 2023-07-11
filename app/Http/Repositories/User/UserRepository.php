<?php

namespace App\Http\Repositories\User; 

use App\Exceptions\InvalidCredentialsException;
use App\Exceptions\PasswordMismatchException;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    public function loginUser(array $data) 
    {
        if(!Auth::attempt($data)) {
            throw new InvalidCredentialsException;
        }

        $user = Auth::user();
        $token = $user->createToken('authToken')->plainTextToken; 

        return [
            'user' => $user, 
            'token' => $token
        ];
    }

    public function createUser(array $data)
    {
        if ($data['password'] !== $data['confirm_password']) {
            throw new PasswordMismatchException;
        }

        unset($data['confirm_password']);
        $data['password'] = Hash::make($data['password']);

        return User::create($data);
    }
}