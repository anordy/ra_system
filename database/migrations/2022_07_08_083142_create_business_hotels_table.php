<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessHotelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_hotels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('location_id');
            $table->string('business_reg_no')->nullable(); // If business is not owned by the current user TODO: replace with business id ? 😃
            $table->string('company_name')->nullable();
            $table->string('management_company')->nullable();
            $table->string('hotel_location');
            $table->unsignedInteger('number_of_rooms');
            $table->unsignedInteger('number_of_single_rooms');
            $table->unsignedInteger('number_of_double_rooms');
            $table->unsignedInteger('number_of_other_rooms');
            $table->unsignedInteger('hotel_capacity');
            $table->string('average_rate');
            $table->enum('rate_currency',['TZS','USD'])->nullable();
            $table->text('other_services')->nullable();
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
        Schema::dropIfExists('business_hotels');
    }
}
