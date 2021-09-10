<?php

namespace App\Services\PaymentServices;

use App\Services\PaymentServices\Payloads\InitiatePayment;
use App\Services\PaymentServices\Payloads\VerifyPayment;
use App\Services\PaymentServices\Responses\PaymentInitiated;
use App\Services\PaymentServices\Responses\PaymentVerified;


class PaymentException extends \Exception {}

interface IPaymentService {
    public function initiate_payment(InitiatePayment $initiate_payment) : PaymentInitiated ;

    public function verify_payment(VerifyPayment $verify_payment ) : PaymentVerified ;

}
