<?php
namespace App\Repository;

use App\Models\WalletTransaction;
use App\Repository\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class WalletTransactionRepository extends BaseRepository implements IWalletTransactionRepository
{

   public function __construct(WalletTransaction $model)
   {
       parent::__construct($model);
   }


   public function all() : Collection {
      return $this->model->all();
   }


   public function paginate(int $number) : LengthAwarePaginator {
      return $this->model->paginate($number);
   }


   public function get_by_wallet_id(int $wallet_id, int $number = 10) : LengthAwarePaginator {
      return $this->model->where("wallet_id",$wallet_id)->paginate($number);
   }






   

}