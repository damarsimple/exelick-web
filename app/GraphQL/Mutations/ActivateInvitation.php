<?php

namespace App\GraphQL\Mutations;

use App\Models\User;

class ActivateInvitation
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $uuid = $args['uuid'];

        $user = User::where([
            'username' => $uuid,
            'is_active' => false
        ])->firstOrFail();

        unset($args['uuid']);

        $input = $args;

        $input['is_active'] = true;

        $user->update($input);

        return $user;
    }
}
