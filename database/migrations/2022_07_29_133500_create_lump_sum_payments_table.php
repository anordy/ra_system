<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLumpSumPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lump_sum_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('filled_id');
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('financial_year_id');
            $table->integer('annual_estimate');
            $table->integer('payment_quarters');
            $table->string('currency');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lump_sum_payments');
    }
}
