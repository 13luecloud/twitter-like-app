<?php

namespace App\Http\Repositories\User; 

interface UserRepositoryInterface 
{
    public function loginUser(array $data);
    public function createUser(array $data);
}