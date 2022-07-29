<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotelLevyReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotel_levy_returns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_location_id');
            $table->unsignedBigInteger('business_id');

            $table->string('filled_type');
            $table->unsignedBigInteger('filled_id');

            $table->integer('total_pax')->nullable();
            $table->decimal('rate_of_charge_per_single_room')->nullable();
            $table->decimal('rate_of_charge_per_double_room')->nullable();
            $table->decimal('rate_of_charge_per_tripple_room')->nullable();
            $table->decimal('rate_of_charge_per_other_room')->nullable();
            $table->integer('no_of_bed_nights')->nullable();


            // 
            


            $table->foreign('business_location_id')->references('id')->on('business_locations');
            $table->foreign('business_id')->references('id')->on('businesses');
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
        Schema::dropIfExists('hotel_levy_returns');
    }
}
