<?php

namespace App\GraphQL\Mutations;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Login
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {


        $email = $args['email'];
        $password = $args['password'];

        try {
            $user = User::where('email', $email)->firstOrFail();
        } catch (\Throwable $th) {
            return [
                'message' => 'User tidak ditemukan ' . $email,
            ];
        }

        $email = $user?->email;

        $credentials = [
            'email' => $email,
            'password' => $password,
        ];

        if (Hash::check($credentials['password'], $user->password)) {
            $user = User::where('email', $email)->firstOrFail();
            return [
                "user" => $user,
                "token" => $user->createToken($user->name)->plainTextToken,
            ];
        } else {
            return [
                'message' => 'Kredensial ini salah.',
            ];
        }
    }
}
