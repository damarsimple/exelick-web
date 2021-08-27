<?php

namespace App\GraphQL\Mutations;

use App\Jobs\ProcessAttachmentJob;
use App\Models\Picture;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

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

        $picture->user_id = Auth::user()->id;

        $picture->save();

        // ProcessAttachmentJob::dispatch($picture, $path);

        $file->move(storage_path('temp'), $name);

        $path = storage_path('temp') . '/' .   $name;


        // $response = Http::post('http://127.0.0.1:3000/', [
        //     'path' => $path,
        // ])->json();

        // if (array_key_exists('cid', $response)) {
        //     $picture->cid = $response['cid'];
        //     $picture->save();
        // }

        try {
            $process = new Process(['node', 'upload.mjs', $path]);

            $process = $process->setWorkingDirectory(base_path());

            $process->mustRun();

            $output = $process->getOutput();

            $output = str_replace($path, '', $output);

            $picture->cid = json_decode($output)->cid;

            $picture->save();
        } catch (ProcessFailedException $exception) {
            // echo $exception->getMessage();
        }



        unlink($path);

        return $picture;
    }
}
