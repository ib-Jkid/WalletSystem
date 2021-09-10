<?php

namespace App\Services\PaymentServices;

use Exception;

class PaymentServicesFactory {
    public static function get_payment_intance($gateway = null ) : IPaymentService {

        if(!$gateway) $gateway = env("DEFAULT_PAYMENT_GATEWAY");

        switch($gateway) {
            case PAYSTACK_GATEWAY: 

                return new PaystackPaymentService;

            break;
            default: 

                throw new Exception("Invalid payment gateway");

            break;
        }   
    }
}