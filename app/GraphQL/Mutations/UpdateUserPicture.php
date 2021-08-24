<?php

namespace App\GraphQL\Mutations;

use App\Enum\PictureRole;
use App\Models\Picture;
use App\Models\User;

class UpdateUserPicture
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $user = User::findOrFail($args['id']);

        if (array_key_exists('banner', $args)) {

            if ($user->banner()->exists()) {
                $user->banner()->delete();
            }

            $picture = Picture::findOrFail($args['banner']['id']);
            $picture->roles =  PictureRole::BANNER;

            $picture->attachable_id = $user->id;
            $picture->attachable_type = 'App\Models\User';

            $picture->save();
        }

        if (array_key_exists('profilepicture', $args)) {

            if ($user->profilepicture()->exists()) {
                $user->profilepicture()->delete();
            }

            $picture = Picture::findOrFail($args['profilepicture']['id']);
            $picture->roles =  PictureRole::PROFILE_PICTURE;

            $picture->attachable_id = $user->id;
            $picture->attachable_type = 'App\Models\User';

            $picture->save();
        }

        return $user;
    }
}
