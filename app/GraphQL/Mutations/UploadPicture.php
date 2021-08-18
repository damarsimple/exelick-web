<?php

namespace App\GraphQL\Mutations;

use App\Jobs\ProcessAttachmentJob;
use App\Models\Picture;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use SebastianBergmann\CodeCoverage\Report\PHP;

class UploadPicture
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        /** @var \Illuminate\Http\UploadedFile $file */
        $file = $args['file'];



        $ext = $file->getClientOriginalExtension();

        $name = Str::uuid() . '.' . $ext;

        $mime = $file->getMimeType();

        $picture = new Picture();

        $picture->name = $name;

        $picture->mime = $mime;

        $picture->save();

        // ProcessAttachmentJob::dispatch($picture, $path);


        $file->move(storage_path('temp'), $name);

        $path = storage_path('temp') . '/' .   $name;

        $response = Http::post('http://127.0.0.1:3000/', [
            'path' => $path,
        ])->json();

        if (array_key_exists('cid', $response)) {
            $picture->cid = $response['cid'];
            $picture->save();
        }

        unlink($path);

        return $picture;
    }
}
