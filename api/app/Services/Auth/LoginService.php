<?php

namespace App\Services\Auth;

use App\Models\User;

class LoginService
{
    public function run(array $data): string
    {
        $user = User::where('username', $data['login'])->orWhere('email', $data['login'])->first();

        return $user->createToken('auth_token')->plainTextToken;
    }
}
