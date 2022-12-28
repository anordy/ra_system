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
            $table->string('sp_code')->nullable();
            $table->string('pay_ref_id')->nullable();
            $table->string('control_number');
            $table->decimal('bill_amount', 20, 2);
            $table->decimal('paid_amount', 20, 2);
            $table->string('bill_pay_opt');
            $table->string('currency');
            $table->timestamp('trx_time')->nullable();
            $table->string('usd_pay_channel')->nullable();
            $table->string('payer_phone_number')->nullable();
            $table->string('payer_name')->nullable();
            $table->string('payer_email')->nullable();
            $table->string('psp_receipt_number')->nullable();
            $table->string('psp_name')->nullable();
            $table->string('ctr_acc_num')->nullable();
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
