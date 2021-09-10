<?php 


namespace App\Services\PaymentServices\Responses;


class PaymentInitiated {
    public $amount;
    public $link;
    public $reference;
    public $gateway;


    public function __construct(int $amount, string $link, string $reference, string $gateway)
    {
        $this->amount = $amount;
        $this->link = $link;
        $this->reference = $reference;
        $this->gateway = $gateway;
    }


    public function toArray() {
        return  [
            "amount" => $this->amount,
            "link" => $this->link,
            "reference" => $this->reference
        ];
    }

}