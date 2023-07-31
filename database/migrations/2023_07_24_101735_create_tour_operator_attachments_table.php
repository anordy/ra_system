<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTourOperatorAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tour_operator_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_location_id');
            $table->unsignedBigInteger('financial_month_id');
            $table->unsignedBigInteger('hotel_return_id')->nullable();
            $table->integer('no_of_days');
            $table->integer('no_of_pax_for_r');
            $table->integer('no_of_pax_for_nr');
            $table->integer('total_no_of_pax');
            $table->decimal('revenue_transfer', 20,2);
            $table->decimal('revenue_excussion', 20,2);
            $table->decimal('other_service', 20,2);
            $table->decimal('total_revenue', 20,2);
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
        Schema::dropIfExists('tour_operator_attachments');
    }
}
