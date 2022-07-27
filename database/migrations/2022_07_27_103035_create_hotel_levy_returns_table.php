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
            $table->unsignedBigInteger('submitted_by');

            $table->integer('total_pax')->nullable();
            $table->float('hotel_supplies')->nullable();
            $table->float('no_of_bed_nights')->nullable();
            $table->float('restaurant_supplies')->nullable();
            $table->float('tour_operation_services')->nullable();
            $table->float('other_supplies')->nullable();

            $table->float('local_purchases')->nullable();
            $table->float('import_purchases')->nullable();
            $table->float('infrastructure_tax')->nullable();
            $table->float('total_levy_amount_due')->nullable();
            $table->float('rate_of_charge_per_single_room')->nullable();
            $table->float('rate_of_charge_per_double_room')->nullable();
            $table->float('rate_of_charge_per_tripple_room')->nullable();
            $table->float('rate_of_charge_per_other_room')->nullable();

            $table->foreign('business_location_id')->references('id')->on('business_locations');
            $table->foreign('submitted_by')->references('id')->on('taxpayers');
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
