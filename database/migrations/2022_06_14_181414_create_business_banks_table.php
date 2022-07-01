<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_banks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('bank_id');
            $table->unsignedBigInteger('account_type_id');
            $table->unsignedBigInteger('currency_id');
            $table->string('acc_no');
            $table->string('branch');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('business_id')->references('id')->on('businesses');
            $table->foreign('bank_id')->references('id')->on('banks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_banks');
    }
}
