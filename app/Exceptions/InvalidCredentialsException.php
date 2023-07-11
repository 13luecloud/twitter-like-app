<?php

namespace App\Exceptions;

use Exception;

class InvalidCredentialsException extends Exception
{
    public function render($request)
    {
        return response()->error(
            'Invalid Credentials', 
            ['You have entered an invalid username or password'],
            401
        );
    }
}
