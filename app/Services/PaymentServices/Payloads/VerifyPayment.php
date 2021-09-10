<?php 


namespace App\Services\PaymentServices\Payloads;


class VerifyPayment {

    public $reference;
    public $amount;


    public function __construct(string $reference, int $amount)
    {
        $this->reference = $reference;
        $this->amount = $amount;
    }
}