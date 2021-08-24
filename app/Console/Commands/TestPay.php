<?php

namespace App\Console\Commands;

use App\Libraries\Tripay;
use App\Libraries\Xendit;
use Illuminate\Console\Command;

class TestPay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pay:test';

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


        $xendit = new Xendit();

        $params = [
            'external_id' => 'demo_14758019262270',
            'payer_email' => 'sample_email@xendit.co',
            'description' => 'Trip to Bali',
            'amount' => 32000
        ];

        $d =  $xendit->makeInvoice($params);

        print_r($d);

        // $tripay = new Tripay();

        // $tripay->setChannel('QRISC');

        // $tripay->setAmount(10000);

        // print('qrisc amount' . PHP_EOL);




        // $data = [
        //     'customer_name' => 'test ppl',
        //     'customer_email' => 'test@ppl.com',
        //     'customer_phone' => '08987181017',
        //     'order_items' => [
        //         [
        //             'price' => 10000,
        //             'quantity' => 1,
        //             'name' => 'test'
        //         ]
        //     ],
        // ];

        // $x = $tripay->makeTransaction($data);

        // // $x = $tripay->makeTransaction('Donasi untuk Someone sebesar UwU');

        // print($x->amount_received);

        return 0;
    }
}
