<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZmBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zm_bills', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount');
            $table->decimal('paid_amount')->default(0);
            $table->enum('currency', ['TZS', 'USD']);
            $table->decimal('exchange_rate');
            $table->decimal('equivalent_amount');
            $table->string('control_number');
            $table->date('expire_on');
            $table->unsignedBigInteger('user_id');
            $table->string('user_type');
            $table->string('payer_name');
            $table->string('payer_phone_number');
            $table->string('payer_email');
            $table->string('description');
            $table->integer('payment_option');
            $table->enum('status',['pending', 'paid', 'partially' , 'failed']);
            $table->string('zan_status');
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
        Schema::dropIfExists('zm_bills');
    }
}
