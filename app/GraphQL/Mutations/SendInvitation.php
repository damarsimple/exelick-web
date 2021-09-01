<?php

namespace App\GraphQL\Mutations;
use App\Models\User;
use Illuminate\Support\Str;

class SendInvitation
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        if(User::where('email', $args['email'])->exists()){
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