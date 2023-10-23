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
        Schema::create('mvr_vehicle_owners', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mvr_motor_vehicle_id');
            $table->string('taxpayer_id');
            $table->date('date')->nullable();
            $table->unsignedBigInteger('mvr_ownership_status_id');
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mvr_vehicle_owners');
    }
}
