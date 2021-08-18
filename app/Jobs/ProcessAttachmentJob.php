<?php

namespace App\Jobs;

use App\Models\Picture;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class ProcessAttachmentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public Picture $picture,
        public string $path
    ) {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $picture = $this->picture;
        $path = $this->path;

        $response = Http::post('http://127.0.0.1/', [
            'path' => $path,
        ])->json();

        if (array_key_exists('cid', $response)) {
            $picture->cid = $response['cid'];
            $picture->save();
            return 0;
        } else {
            return 1;
        }
    }
}
