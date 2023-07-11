<?php

namespace App\Http\Repositories\User; 

use App\Exceptions\InvalidCredentialsException;

use Illuminate\Support\Facades\Auth;

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
}