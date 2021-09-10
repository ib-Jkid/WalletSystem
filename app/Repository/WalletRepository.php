<?php
namespace App\Repository;

use App\Models\Wallet;
use App\Repository\Eloquent\BaseRepository;
use Faker\Provider\Uuid as ProviderUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

class WalletRepository extends BaseRepository implements IWalletRepository
{
   private $wallet_transaction_repo;

   public function __construct(Wallet $model, IWalletTransactionRepository $wallet_transaction_repo)
   {
       parent::__construct($model);

       $this->wallet_transaction_repo = $wallet_transaction_repo;
   }


   public function all() : Collection {
      return $this->model->all();
   }


   public function paginate(int $number) : Collection {
      return $this->model->paginate($number);
   }


   public function find_by_public_id($id) : ?Model {
      return $this->model->where("public_id", $id)->first();
   }


   public function fund_wallet($id,$amount) : bool {

      $wallet = $this->find($id);


      $new_amount = $wallet->amount + $amount;

      $this->wallet_transaction_repo->create([
         "request_id" => REQUEST_ID,
         "wallet_id" => $id,
         "public_id" => ProviderUuid::uuid(),
         "type" => "credit",
         "amount" => $amount,
         "previous_amount" => $wallet->amount,
         "current_amount" => $new_amount
      ]);


      return $this->update($id, [
         "amount" => $new_amount
      ]);

      

      
   }






   

}