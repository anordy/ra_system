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
            $table->bigInteger('business_id');
            $table->double('latitude');
            $table->double('longitude');
            $table->enum('nature_of_premises',['OWNED','RENTED']);
            $table->string('street');
            $table->string('owner_name')->nullable();
            $table->string('owner_phone_no')->nullable();
            $table->integer('meter_no');
            $table->boolean('is_headquarter')->default(0);
            $table->timestamps();

            $table->foreign('business_id')->references('id')->on('businesses');
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
