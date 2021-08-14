<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Seed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:make';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $user = new User();
        $user->email = "damaralbaribin@gmail.com";
        $user->username = "damaralbaribin";
        $user->name = "Damar Albaribin";
        $user->tag = "Admin";
        $user->description = "aku adalah simper hololive nomor 1 di indonesia";
        $user->stream_key = Str::random(10);
        $user->email_verified_at = now();
        $user->password =  Hash::make('123456789');

        $user->save();

        User::factory()->count(100)->create();
        return 0;
    }
}
