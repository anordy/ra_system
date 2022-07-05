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
            $table->decimal('misc_amount')->default(0);
            $table->decimal('paid_amount')->default(0);
            $table->enum('currency', ['TZS', 'USD']);
            $table->decimal('exchange_rate');
            $table->decimal('equivalent_amount')->default(0);
            $table->string('control_number')->nullable();
            $table->date('expire_date');
            $table->unsignedBigInteger('payer_id');
            $table->string('payer_type');
            $table->string('payer_name');
            $table->string('payer_phone_number')->nullable();
            $table->string('payer_email')->nullable();
            $table->string('description')->nullable();
            $table->integer('payment_option');
            $table->enum('status',['pending', 'paid', 'partially' , 'failed', 'cancelled']);
            $table->string('cancellation_reason')->nullable();
            $table->string('zan_status')->nullable();
            $table->string('zan_trx_sts_code')->nullable();
            $table->unsignedBigInteger('createdby_id')->nullable();
            $table->string('createdby_type')->nullable();
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
