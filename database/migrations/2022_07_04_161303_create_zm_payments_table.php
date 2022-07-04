<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZmPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zm_payments', function (Blueprint $table) {
            $table->id();
            $table->string('zm_bill_id');
            $table->string('trx_id');
            $table->string('pay_ref_id');
            $table->string('control_number');
            $table->string('bill_amount');
            $table->string('paid_amount');
            $table->string('bill_pay_out');
            $table->string('currency');
            $table->string('trx_time');
            $table->string('usd_pay_channel');
            $table->string('payer_phone_number');
            $table->string('payer_name');
            $table->string('payer_email');
            $table->string('payer_receipt_number');
            $table->string('psp_name');
            $table->string('crt_acc_num');
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
        Schema::dropIfExists('zm_payments');
    }
}
