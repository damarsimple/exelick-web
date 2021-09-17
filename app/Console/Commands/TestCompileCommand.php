<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class TestCompileCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:test';

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
        $user = User::latest()->first();
        $product = $user->products()->first();
        var_dump($product);
        $variables = [];

        foreach ($user->variables as $var) {
            $variables[$var['name']] = $var['value'];
        }
        foreach ($product->commands as $command) {
            $words = explode(" ", $command);
            $newWords = [];
            foreach ($words as $word) {
                try {
                    if ($word[0] == "$") {
                        $word = str_replace("$", "", $word);
                        $word = $variables[$word];
                    }
                    $newWords[] = $word;
                } catch (\Throwable $th) {
                    var_dump($variables);
                    print($th->getMessage() . PHP_EOL);
                    continue;
                }
            }
            print(implode(" ", $newWords) . PHP_EOL);

            Redis::publish('mc_' . $user->stream_key, implode(" ", $newWords));
        }

        return 0;
    }
}
