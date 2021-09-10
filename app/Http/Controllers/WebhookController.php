<?php

namespace App\Http\Controllers;

use App\Repository\IWalletFundingTransactionRepository;
use App\Repository\IWalletRepository;
use App\Repository\IWalletTransactionRepository;
use App\Services\PaymentServices\Payloads\VerifyPayment;
use App\Services\PaymentServices\PaymentServicesFactory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{

    private $wallet_funding_repository;
    private $wallet_repository;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(IWalletFundingTransactionRepository $wallet_funding_repository, IWalletRepository $wallet_repository )
    {
        //
        $this->wallet_funding_repository = $wallet_funding_repository;
        $this->wallet_repository = $wallet_repository;
    }

    //

    public function payment_callback_paystack(Request $request) {

        $payment = PaymentServicesFactory::get_payment_intance(PAYSTACK_GATEWAY);

        $response = $payment->verify_payment(new VerifyPayment($request->reference,0));

        //check for pending transactions
        $funding_transaction = $this->wallet_funding_repository->find_by_columns(["gateway","gateway_reference","status"],[$response->gateway, $response->reference,false]);


        if(!$funding_transaction) return false;


        $amount = $response->amount - $response->gateway_service_fee;

        DB::beginTransaction();
        try {
            
            $this->wallet_repository->fund_wallet($funding_transaction->wallet_id,$amount);

            $this->wallet_funding_repository->update($funding_transaction->id, [
                "status" => 1
            ]);


            DB::commit();

        }catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }



        return true;

        




       
    }
}
