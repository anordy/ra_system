<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVatCashSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vat_cash_sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_location_id');
            $table->unsignedBigInteger('financial_month_id');
            $table->unsignedBigInteger('vat_return_id');
            $table->integer('from_number');
            $table->integer('to_number');
            $table->string('purchases_type');
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('vat_cash_sales');
    }
}
