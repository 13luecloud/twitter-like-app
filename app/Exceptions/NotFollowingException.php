<?php

namespace App\Exceptions;

use Exception;

class NotFollowingException extends Exception
{
    public function render($request)
    {
        return response()->error(
            'Unauthorized', 
            ["Only followers can see this user's content"], 
            401
        );
    }
}
