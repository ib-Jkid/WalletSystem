<?php

namespace App\Http\Controllers;

use App\Http\Resources\WalletResource;
use App\Http\Resources\WalletTransactionResource;
use App\Models\Wallet;
use App\Repository\IWalletFundingTransactionRepository;
use App\Repository\IWalletRepository;
use App\Repository\IWalletTransactionRepository;
use App\Services\PaymentServices\Payloads\InitiatePayment;
use App\Services\PaymentServices\Payloads\VerifyPayment;
use App\Services\PaymentServices\PaymentException;
use App\Services\PaymentServices\PaymentServicesFactory;
use App\Services\PaymentServices\Responses\PaymentInitiated;
use App\Traits\ApiResponse;
use Exception;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class WalletController extends Controller
{

 

    private $wallet_repository;
    private $wallet_funding_transaction_repository;
    private $wallet_transaction_repository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
 

    public function __construct(IWalletRepository $wallet_repo, IWalletFundingTransactionRepository $funding_transaction_repo, IWalletTransactionRepository $wallet_transaction_repo)
    {
        $this->wallet_repository = $wallet_repo;
        $this->wallet_funding_transaction_repository = $funding_transaction_repo;
        $this->wallet_transaction_repository = $wallet_transaction_repo;

    }
 
    //

    // public function create(Request $request) {
     
    //     $validator = Validator::make($request->all(), [
    //        "currency" => "required|in:".implode(",",SUPPORTED_CURRENCY),
    //        "email" => "required|unique:wallets"
    //     ]);

    //     if($validator->fails()) return $this->bad_validation($validator->errors()->toArray());

      
    //     $wallet = $this->wallet_repository->create([
    //         "public_id" => Uuid::uuid(),
    //         "currency" => $request->currency,
    //         "amount" => 0,
    //         "email" => $request->email
           
    //     ]);


    //     return $this->ok(WalletResource::make($wallet));


    // }


    public function balance_enquiry() { 
 
        $wallet = auth()->user()->wallet;


        if(!$wallet) return $this->not_found();


        return $this->ok(WalletResource::make($wallet));
    }


    public function initiate_wallet_funding(Request $request) {

        $validator = Validator::make($request->all(), [
            "amount" => "required|min:0"
        ]);

        if($validator->fails()) return $this->bad_validation($validator->errors()->toArray());

        $wallet = auth()->user()->wallet;


        if(!$wallet) return $this->not_found();


        $payment = PaymentServicesFactory::get_payment_intance();

        try {
              /** @var PaymentInitiated  */
            $response = $payment->initiate_payment(new InitiatePayment($request->amount, $wallet->email, $wallet->currency));

        }catch(PaymentException $e) {
            return $this->server_error($e->getMessage());
        }

      

        //create wallet funding traansaction

        $this->wallet_funding_transaction_repository->create([
            "request_id" => REQUEST_ID,
            "status" => false,
            "amount" => $request->amount * 100,
            "currency" => $wallet->currency,
            "gateway" => $response->gateway,
            "gateway_reference" => $response->reference,
            "wallet_id" => $wallet->id
        ]);
       

        return $this->ok($response->toArray());
       



    }


    public function transactions(Request $request) {

        $wallet = auth()->user()->wallet;

        if(!$wallet) return $this->not_found();

        $transaction = $this->wallet_transaction_repository->get_by_wallet_id($wallet->id);


        return $this->ok(WalletTransactionResource::collection($transaction));
    }

    public function fund_wallet(Request $request) {

        $validator = Validator::make($request->all(),[
            "reference" => "required"
        ]);

        if($validator->fails()) return $this->bad_validation($validator->errors()->toArray());

        $wallet = auth()->user()->wallet;

        $funding_transaction = $this->wallet_funding_transaction_repository->find_by_columns(["gateway_reference","status","wallet_id"],[$request->reference,false,$wallet->id]);

        if(!$funding_transaction) return $this->not_found();

        $payment = PaymentServicesFactory::get_payment_intance($funding_transaction->gateway);

        $response = $payment->verify_payment(new VerifyPayment($funding_transaction->gateway_reference,$funding_transaction->amount));

        
        $amount = $response->amount - $response->gateway_service_fee;

        DB::beginTransaction();

        try {
            
            $this->wallet_repository->fund_wallet($funding_transaction->wallet_id,$amount);

            $this->wallet_funding_transaction_repository->update($funding_transaction->id, [
                "status" => 1
            ]);


            DB::commit();

        }catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }


        $wallet = $this->wallet_repository->find_by_public_id($wallet->public_id);




        return $this->ok(WalletResource::make($wallet));






    }


 
}
