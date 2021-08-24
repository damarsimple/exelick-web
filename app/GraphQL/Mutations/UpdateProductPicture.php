<?php

namespace App\GraphQL\Mutations;

use App\Enum\PictureRole;
use App\Models\Picture;
use App\Models\Product;

class UpdateProductPicture
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $product = Product::findOrFail($args['id']);

        if (array_key_exists('cover', $args)) {

            if ($product->cover()->exists()) {
                $product->cover()->delete();
            }

            $picture = Picture::findOrFail($args['cover']['id']);
            $picture->roles =  PictureRole::COVER;

            $picture->attachable_id = $product->id;
            $picture->attachable_type = 'App\Models\Product';

            $picture->save();
        }

        return $product;
    }
}
