<?php
namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface IWalletTransactionRepository
{
   public function all(): Collection;


   public function paginate(int $number) : LengthAwarePaginator;


   public function get_by_wallet_id(int $wallet_id, int $number = 10) : LengthAwarePaginator;


}