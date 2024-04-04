<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublicServicePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('public_service_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('public_service_motor_id');
            $table->unsignedBigInteger('public_service_payment_category_id');
            $table->integer('payment_months');
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
        Schema::dropIfExists('public_service_payments');
    }
}
