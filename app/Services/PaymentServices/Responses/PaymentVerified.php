<?php 


namespace App\Services\PaymentServices\Responses;


class PaymentVerified {
    public $amount;
    public $gateway_service_fee;
    public $reference;
    public $gateway;
    public $currency;

    public function __construct(int $amount, int $gateway_service_fee, string $reference, string $gateway, string $currency)
    {
        $this->amount = $amount;
        $this->gateway_service_fee = $gateway_service_fee;
        $this->reference = $reference;
        $this->gateway = $gateway;
        $this->currency = $currency;
    }

}