<?php

namespace App\Services\PaymentServices\Payloads;


class InitiatePayment {

    public $amount;
    public $currency;
    public $email;



    public function __construct(int $amount, string $email, string $currency) {
        $this->amount = $amount;
        $this->currency = $currency;
        $this->email = $email;
    }
}