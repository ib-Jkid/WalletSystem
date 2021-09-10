<?php
namespace App\Repository;

use App\Models\WalletFundingTransaction;
use App\Repository\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class WalletFundingTransactionRepository extends BaseRepository implements IWalletFundingTransactionRepository
{

   public function __construct(WalletFundingTransaction $model)
   {
       parent::__construct($model);
   }


   public function all() : Collection {
      return $this->model->all();
   }


   public function paginate(int $number) : LengthAwarePaginator {
      return $this->model->paginate($number);
   }


   
   public function find_by_columns(array $columns, array $values) : ?Model {
      return $this->model->where(function($query) use($columns, $values) {
         foreach($columns as $index=>$column) {
            $query->where($column,$values[$index]);
         }
      })->first();
   }





   

}