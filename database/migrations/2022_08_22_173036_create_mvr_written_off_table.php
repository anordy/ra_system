<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMvrWrittenOffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mvr_written_off', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mvr_motor_vehicle_id');
            $table->date('date')->useCurrent();
            $table->timestamps();

            $table->foreign('mvr_motor_vehicle_id')->references('id')->on('mvr_motor_vehicles');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mvr_written_off');
    }
}
