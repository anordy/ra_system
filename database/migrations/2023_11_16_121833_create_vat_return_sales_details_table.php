<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVatReturnSalesDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vat_return_sales_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vat_return_id');
            $table->unsignedBigInteger('business_location_id');
            $table->unsignedBigInteger('financial_month_id');
            $table->string('receipt_number');
            $table->timestamp('receipt_date');
            $table->decimal('receipt_amount', 20, 2);
            $table->decimal('vat', 20, 2);
            $table->softDeletes();
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
        Schema::dropIfExists('vat_return_sales_details');
    }
}
