<?php 

namespace App\Providers;

use App\Models\WalletTransaction;
use App\Repository\Eloquent\BaseRepository;
use App\Repository\IEloquentRepository;
use App\Repository\IWalletFundingTransactionRepository;
use App\Repository\IWalletRepository;
use App\Repository\IWalletTransactionRepository;
use App\Repository\WalletFundingTransactionRepository;
use App\Repository\WalletRepository;
use App\Repository\WalletTransactionRepository;
use Illuminate\Support\ServiceProvider; 

/** 
* Class RepositoryServiceProvider 
* @package App\Providers 
*/ 
class RepositoryServiceProvider extends ServiceProvider 
{ 
   /** 
    * Register services. 
    * 
    * @return void  
    */ 
   public function register() 
   { 
       $this->app->bind(IEloquentRepository::class, BaseRepository::class);
       $this->app->bind(IWalletRepository::class, WalletRepository::class);
       $this->app->bind(IWalletTransactionRepository::class, WalletTransactionRepository::class);
       $this->app->bind(IWalletFundingTransactionRepository::class, WalletFundingTransactionRepository::class);


   }
}