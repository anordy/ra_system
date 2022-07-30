<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotelConfigReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotel_config_returns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('config_id');
            $table->string('value');
            $table->string('vat');
            $table->unsignedBigInteger('return_id');
            $table->timestamps();

            $table->foreign('config_id')->references('id')->on('hotel_levy_configs');
            $table->foreign('return_id')->references('id')->on('hotel_levy_returns');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hotel_config_returns');
    }
}
