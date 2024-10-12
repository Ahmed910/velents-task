<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;

class AuthService implements AuthServiceInterface
{
    public function login(string $email, string $password)
    {
        $token = Auth::guard()->attempt(['email' => $email, 'password' => $password]);

        if (!$token) {
            throw new UnauthorizedException("Unauthorized!");
        }

        $user = User::where(['email' => $email])->first();
        $user->access_token = $token;
        $user->token_type = 'bearer';

        return $user;
    }
}
