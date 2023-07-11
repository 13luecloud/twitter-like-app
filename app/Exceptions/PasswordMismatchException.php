<?php

namespace App\Exceptions;

use Exception;

class PasswordMismatchException extends Exception
{
    public function render($request)
    {
        return response()->error(
            'The given data is invalid', 
            ['confirm_password' => 'Password and confirm password do not match'], 
            422
        );
    }
}
