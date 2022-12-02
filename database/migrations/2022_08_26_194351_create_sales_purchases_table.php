<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('return_id');
            $table->unsignedBigInteger('business_location_id');
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('tax_type_id');
            $table->unsignedBigInteger('financial_month_id');
            $table->unsignedBigInteger('financial_year_id');
            $table->string('currency')->nullable();
            $table->decimal('total_sales',20,2);
            $table->decimal('total_purchases',20,2);
            $table->enum('category',['less than 10 percentage','one third of sales']);
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
        Schema::dropIfExists('sales_purchases');
    }
}
