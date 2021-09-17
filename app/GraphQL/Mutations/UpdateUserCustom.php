<?php

namespace App\GraphQL\Mutations;

use App\Models\User;

class UpdateUserCustom
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $user = User::findOrFail($args['id']);

        if (array_key_exists('variables', $args)) {

            $user->variables = $args['variables'];
        }

        $user->save();

        return $user;
    }
}
