<?php

namespace App\Exceptions;

use Exception;

class NotFollowingException extends Exception
{
    public function render($request)
    {
        return response()->error(
            'Unauthorized', 
            ["Sorry, only followers can see this user's content"], 
            401
        );
    }
}
