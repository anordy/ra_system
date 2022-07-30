<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotelReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotel_returns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_location_id');
            $table->unsignedBigInteger('business_id');

            $table->string('filled_type');
            $table->unsignedBigInteger('filled_id');
            $table->unsignedBigInteger('taxtype_id');
            $table->unsignedBigInteger('financial_year_id');

            $table->integer('edited_count')->default(0);

            $table->decimal('total', 40, 12);
            $table->decimal('infrastructure_tax')->nullable();

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
        Schema::dropIfExists('hotel_returns');
    }
}
