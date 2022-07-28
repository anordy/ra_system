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
            $table->decimal('hotel_supplies')->nullable();
            $table->decimal('no_of_bed_nights')->nullable();
            $table->decimal('restaurant_supplies')->nullable();
            $table->decimal('tour_operation_services')->nullable();
            $table->decimal('other_supplies')->nullable();

            $table->decimal('local_purchases')->nullable();
            $table->decimal('import_purchases')->nullable();
            $table->decimal('infrastructure_tax')->nullable();
            $table->decimal('total_levy_amount_due')->nullable();
            $table->decimal('rate_of_charge_per_single_room')->nullable();
            $table->decimal('rate_of_charge_per_double_room')->nullable();
            $table->decimal('rate_of_charge_per_tripple_room')->nullable();
            $table->decimal('rate_of_charge_per_other_room')->nullable();

            $table->decimal('subtotal');
            $table->decimal('total');

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
