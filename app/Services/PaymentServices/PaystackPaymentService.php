<?php 

namespace App\Services\PaymentServices;

use App\Services\PaymentServices\Payloads\InitiatePayment;
use App\Services\PaymentServices\Payloads\VerifyPayment;
use App\Services\PaymentServices\Responses\PaymentInitiated;
use App\Services\PaymentServices\Responses\PaymentVerified;
use Exception;
use Illuminate\Support\Facades\Log;


class PaystackPaymentService implements IPaymentService {

    private $secret_key;
    private $public_key;

    private $url;

    public function __construct()
    {
        $this->secret_key = env(env("APPLICATION_MODE")."_PAYSTACK_SECRET_KEY");

        $this->public_key = env(env("APPLICATION_MODE")."_PAYSTACK_PUBLIC_KEY");

        $this->url =  env(env("APPLICATION_MODE")."_PAYSTACK_URL");

    }

    public function initiate_payment(InitiatePayment $initiate_payment) : PaymentInitiated  {

      

        $url = "{$this->url}/transaction/initialize";

        $fields = [
          'email' => $initiate_payment->email,
          'amount' => $initiate_payment->amount * 100,
          'currency' => $initiate_payment->currency,
          "callback_url" => env("PAYSTACK_PAYMENT_CALLBACK_URL")
        ];
        $fields_string = http_build_query($fields);
        //open connection
        $ch = curl_init();
        
        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          "Authorization: Bearer {$this->secret_key}",
          "Cache-Control: no-cache",
        ));

        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        
        //So that curl_exec returns the contents of the cURL; rather than echoing it
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 


     
        
        //execute post
        $result = curl_exec($ch);
        

        $err = curl_error($ch);

        Log::info($err);
        
        Log::info($result);

        if(!$result) throw new PaymentException("Failed to initiate payment");

        $result = json_decode($result,true);

        if(!$result["status"]) {
            throw new PaymentException("Failed to initited payment");
        } 

        $result = $result["data"];

        return new PaymentInitiated($initiate_payment->amount, $result["authorization_url"],$result["reference"],PAYSTACK_GATEWAY);

    }

    public function verify_payment(VerifyPayment $verify_payment) : PaymentVerified {



        $curl = curl_init();
  
        curl_setopt_array($curl, array(
            CURLOPT_URL => "{$this->url}/transaction/verify/{$verify_payment->reference}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer {$this->secret_key}",
                "Cache-Control: no-cache",
            ),
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        
        if ($err) {
           throw new Exception($err);
        } 


        Log::info($response);

        if(!$response) {
            throw new PaymentException("Payment Verification Failed");
        }


        $response = json_decode($response,true);


        if(!$response["status"] || $response["data"]["status"] != "success") {
            throw new PaymentException("Payment not completed");
        }




        $response = $response["data"];


        return new PaymentVerified($response["amount"],$response["fees"],$response["reference"],PAYSTACK_GATEWAY,$response["currency"]);

    }
}