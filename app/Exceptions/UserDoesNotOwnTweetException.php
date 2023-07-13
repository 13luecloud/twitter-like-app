<?php

namespace App\Exceptions;

use Exception;

class UserDoesNotOwnTweetException extends Exception
{
    public function render($request)
    {
        return response()->error(
            'Unauthorized', 
            ["Sorry, your request cannot be processed"], 
            401
        );
    }
}
