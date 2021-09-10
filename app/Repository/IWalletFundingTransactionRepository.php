<?php
namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface IWalletFundingTransactionRepository
{
   public function all(): Collection;


   public function paginate(int $number) : LengthAwarePaginator;

   
   public function find_by_columns(array $columns, array $values) : ?Model;

}