<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVfmsReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vfms_receipts', function (Blueprint $table) {
            $table->id();
            $table->string('vfms_receipt_no');
            $table->string('business_name')->nullable();
            $table->string('trade_name')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('unit_name')->nullable();
            $table->string('currency');
            $table->timestamp('issued_date');
            $table->decimal('amount', 20, 2);
            $table->decimal('tax_amount', 20, 2);
            $table->unsignedBigInteger('mvr_registration_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vfms_receipts');
    }
}
