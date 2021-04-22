<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
            'token' => $user->createToken('API Token')->plainTextToken
        ];
    }

    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->all())) {
            return $this->error('Credentials not match', 401);
        }

        return [
            'token' => auth()->user()->createToken('API Token')->plainTextToken
        ];
    }
}
