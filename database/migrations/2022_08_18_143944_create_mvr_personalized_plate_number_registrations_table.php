<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMvrPersonalizedNumberRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mvr_personalized_plate_number_registration', function (Blueprint $table) {
            $table->id();
            $table->string('plate_number');
            $table->enum('status',['PENDING','ACTIVE','REJECTED','EXPIRED','RETIRED']);
            $table->unsignedBigInteger('mvr_motor_vehicle_registration_id');
            $table->timestamps();

            $table->foreign('mvr_motor_vehicle_registration_id','mmvrgpnr_vr_motor_vehicle_registration')->references('id')->on('mvr_motor_vehicle_registration');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mvr_golden_plate_number_registrations');
    }
}
