<?php

namespace App\Libraries;

use Xendit\Invoice;
use Xendit\Xendit as XenditPayment;

XenditPayment::setApiKey('xnd_development_UOLWxmIgBTcD2Do74YckHjC4hzln2vScgq6O8FyqgmGwyuC0KNGpVUjtqJQjXNS');

class Xendit
{


    /** 
     *         
     *   $params = [
     *       'external_id' => 'demo_147580196270',
     *       'payer_email' => 'sample_email@xendit.co',
     *       'description' => 'Trip to Bali',
     *       'amount' => 32000
     *   ];
     *
     */

    public function makeInvoice(array $params)
    {

        $createInvoice = Invoice::create($params);

        return $createInvoice;
    }
}
