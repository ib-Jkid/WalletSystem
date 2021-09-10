<?php
namespace App\Repository;

use App\Model\Wallet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface IWalletRepository
{
   public function all(): Collection;


   public function paginate(int $number) : Collection;

   public function find_by_public_id($id) : ?Model;
   

   public function fund_wallet($id,$amount) : bool;


}