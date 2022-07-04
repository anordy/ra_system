<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZmBillItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zm_bill_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('zm_bill_id');
            $table->unsignedBigInteger('billable_id');
            $table->string('billable_type');
            $table->unsignedBigInteger('fee_id');
            $table->string('fee_type');
            $table->decimal('amount');
            $table->decimal('exchange_rate');
            $table->decimal('equivalent_amount');
            $table->enum('currency', ['TZS', 'USD']);
            $table->boolean('paid')->default(false);
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
        Schema::dropIfExists('zm_bill_items');
    }
}
