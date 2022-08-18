<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMvrMotorVehicleRegistrationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mvr_motor_vehicle_registration', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mvr_plate_size_id');
            $table->string('plate_number',30);
            $table->unsignedBigInteger('mvr_motor_vehicle_id');
            $table->unsignedBigInteger('mvr_registration_type_id');
            $table->date('registration_date')->useCurrent();
            $table->unsignedBigInteger('mvr_plate_number_status_id');
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
        Schema::dropIfExists('mvr_motor_vehicle_registration');
    }
}
