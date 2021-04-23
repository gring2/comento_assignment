<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Exceptions\UnAuthorizedException;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;

use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->get('name'),
            'password' => bcrypt($request->get('password')),
            'email' => $request->get('email'),
        ]);

        return [
            'token' => $user->createToken($request->get('email'))->plainTextToken
        ];
    }

    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->all())) {
            throw new UnAuthorizedException();
        }

        return [
            'token' =>  $request->user()->createToken($request->user()->email)->plainTextToken
        ];
    }
}
