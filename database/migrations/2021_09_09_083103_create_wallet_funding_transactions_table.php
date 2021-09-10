<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletFundingTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_funding_transactions', function (Blueprint $table) {
            $table->id();
            $table->string("request_id",100)->unique();
            $table->unsignedBigInteger("wallet_id");
            $table->boolean("status");
            $table->unsignedInteger("amount");
            $table->string("currency");
            $table->string("gateway");
            $table->string("gateway_reference",100)->unique();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallet_funding_transactions');
    }
}
