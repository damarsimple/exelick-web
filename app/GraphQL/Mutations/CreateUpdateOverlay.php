<?php

namespace App\GraphQL\Mutations;

use App\Enum\PictureRole;
use App\Models\Audio;
use App\Models\Overlay;
use App\Models\Picture;

class CreateUpdateOverlay
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $user = auth()->user();

        $overlay = Overlay::firstOrCreate([
            'user_id' => $user->id,
            'type' => $args['type'],
        ]);

        $overlay = Overlay::find($overlay->id);

        if (array_key_exists('audio_id', $args)) {
            if ($overlay->audio()->exists()) $overlay->audio()->delete();

            $audio = Audio::find($args['audio_id']);
            $audio->attachable_id = $overlay->id;
            $audio->attachable_type = 'App\Models\Overlay';

            $audio->save();
        }

        if (array_key_exists('picture_id', $args)) {
            if ($overlay->thumbnail()->exists()) $overlay->thumbnail()->delete();

            $picture = Picture::find($args['picture_id']);
            $picture->roles =  PictureRole::THUMBNAIL;
            $picture->attachable_id = $overlay->id;
            $picture->attachable_type = 'App\Models\Overlay';

            $picture->save();
        }

        unset($args['type']);

        $overlay->metadata = $args;

        $overlay->save();



        return $overlay;
    }
}
