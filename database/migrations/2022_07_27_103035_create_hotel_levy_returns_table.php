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
            $table->longText('hotel_supplies')->nullable();
            $table->longText('no_of_bed_nights')->nullable();
            $table->longText('restaurant_supplies')->nullable();
            $table->longText('tour_operation_services')->nullable();
            $table->longText('other_supplies')->nullable();

            $table->longText('local_purchases')->nullable();
            $table->longText('import_purchases')->nullable();
            $table->longText('infrastructure_tax')->nullable();
            $table->longText('total_levy_amount_due')->nullable();
            $table->decimal('rate_of_charge_per_single_room')->nullable();
            $table->decimal('rate_of_charge_per_double_room')->nullable();
            $table->decimal('rate_of_charge_per_tripple_room')->nullable();
            $table->decimal('rate_of_charge_per_other_room')->nullable();

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
