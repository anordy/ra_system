<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVatHotelDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vat_hotel_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('financial_month_id');
            $table->unsignedBigInteger('vat_return_id')->nullable();
            $table->integer('no_of_pax_for_r');
            $table->integer('no_of_pax_for_nr');
            $table->integer('total_no_of_pax');
            $table->decimal('total_room_revenue', 20,2);
            $table->decimal('revenue_for_food', 20,2);
            $table->decimal('revenue_for_beverage', 20,2);
            $table->decimal('total_revenue', 20,2);
            $table->decimal('other_revenue', 20,2)->nullable();
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
        Schema::dropIfExists('vat_hotel_details');
    }
}
