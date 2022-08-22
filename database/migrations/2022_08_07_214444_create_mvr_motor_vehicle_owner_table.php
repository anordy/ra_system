<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMvrMotorVehicleOwnerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mvr_motor_vehicle_owners', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('mvr_motor_vehicle_id');
            $table->unsignedBigInteger('taxpayer_id');
            $table->date('date')->nullable()->comment('Initial date registered as owner');
            $table->unsignedBigInteger('mvr_ownership_status_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('mvr_motor_vehicle_id')->references('id')->on('mvr_motor_vehicles');
            $table->foreign('taxpayer_id')->references('id')->on('taxpayers');
            $table->foreign('mvr_ownership_status_id')->references('id')->on('mvr_ownership_status');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mvr_motor_vehicle_owners');
    }
}
