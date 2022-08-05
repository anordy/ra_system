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
            $table->unsignedBigInteger('fee_id')->nullable();
            $table->string('fee_type')->nullable();
            $table->decimal('amount', 20, 2);
            $table->decimal('exchange_rate');
            $table->decimal('equivalent_amount');
            $table->enum('currency', ['TZS', 'USD']);
            $table->boolean('paid')->default(false);
            $table->char('use_item_ref_on_pay')->default('N');
            $table->string('gfs_code');

            $table->unsignedBigInteger('tax_type_id');
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
