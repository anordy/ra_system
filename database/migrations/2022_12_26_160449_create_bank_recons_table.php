<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankReconsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_recons', function (Blueprint $table) {
            $table->id();
            $table->date('transaction_date');
            $table->date('actual_transaction_date');
            $table->string('transaction_type');
            $table->string('control_no');
            $table->string('payer_name');
            $table->string('payment_ref');
            $table->string('transaction_origin');
            // transaction type, Discard Balance
            // control no, CS => T, TB => STarts with T,
            // Payer Name, CS => After CN(T),
            // Payment Ref, CS => After Payer Name, TB => Starts with RT
            // Transaction Origin, CS => After FROM, TB => POS
            $table->decimal('debit_amount', 38, 2);
            $table->decimal('credit_amount', 38, 2);
            $table->decimal('current_balance', 38, 2);
            $table->decimal('dr_cr');
            $table->decimal('doc_num');
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
        Schema::dropIfExists('bank_recons');
    }
}
