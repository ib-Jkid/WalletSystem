<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->string("request_id",100)->unique();
            $table->unsignedInteger("wallet_id");
            $table->string("public_id");
            $table->enum("type",["credit","debit"]);
            $table->unsignedInteger("amount");
            $table->unsignedBigInteger("previous_amount");
            $table->unsignedBigInteger("current_amount");
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
        Schema::dropIfExists('wallet_transactions');
    }
}
