<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_locations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');
            $table->double('latitude');
            $table->double('longitude');
            $table->enum('nature_of_possession',['Owned','Rented']);
            $table->unsignedBigInteger('ward_id');
            $table->string('street');
            $table->string('address');
            $table->string('house_no');
            $table->string('owner_name')->nullable();
            $table->string('owner_phone_no')->nullable();
            $table->string('business_phone_no');
            $table->string('business_alt_phone_no')->nullable();
            $table->string('email')->unique();
            $table->integer('house_meter_no');
            $table->boolean('is_headquarter')->default(0);
            $table->timestamps();

            $table->foreign('ward_id')->references('id')->on('wards');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_locations');
    }
}
