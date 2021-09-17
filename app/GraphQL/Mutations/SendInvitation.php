<?php

namespace App\GraphQL\Mutations;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\Invitation;

class SendInvitation
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {

        if (!auth()->user()->is_admin) {
            return ['status' => false, 'message' => 'you dont have authorization to do this commands'];
        }
        if (User::where('email', $args['email'])->exists()) {
            return ['status' => false, 'message' => 'user exists'];
        }

        $user = new User();
        $user->email = $args['email'];
        $user->username =  Str::uuid();
        $user->name = "Pengguna Baru";
        $user->tag = "Pengguna Baru";
        $user->description = "Pengguna Baru";
        $user->stream_key = Str::random(10);
        $user->email_verified_at = now();
        $user->password =  Hash::make(Str::random(10));
        $user->save();

        Mail::to($args['email'])->send(new Invitation($user->username));

        return ['status' => true, 'message' => 'success invitation'];
    }
}
