<?php


namespace App\Http\Exceptions;

use Exception;

class UnAuthorizedException extends Exception
{
    public function render($request)
    {
        return response()->json(['error' => 'Unauthenticated.'], 401);
    }
}
