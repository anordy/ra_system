<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessBankAccsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_bank_accs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('business_id');
            $table->bigInteger('bank_id');
            $table->string('acc_no');
            $table->enum('acc_type',['TYPE1','TYPE2','TYPE3']);
            $table->string('branch');
            $table->enum('currency',['TZS','USD']);
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
        Schema::dropIfExists('business_bank_accs');
    }
}
