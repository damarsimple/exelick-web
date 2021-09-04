<?php

namespace App\GraphQL\Queries;

use App\Models\User;

class QueryInvitation
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $username = $args['username'];

        return User::where([
            'username' => $username,
            'is_active' => false
        ])->firstOrFail();
    }
}
