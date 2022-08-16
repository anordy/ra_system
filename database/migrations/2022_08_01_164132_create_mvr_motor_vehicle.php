<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMvrMotorVehicle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mvr_motor_vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number',20);
            $table->smallInteger('number_of_axle');
            $table->string('chassis_number',20);
            $table->integer('year_of_manufacture');
            $table->string('engine_number',30);
            $table->decimal('gross_weight',10);
            $table->string('engine_capacity',20);
            $table->integer('seating_capacity');
            $table->unsignedBigInteger('mvr_vehicle_status_id');
            $table->unsignedBigInteger('imported_from_country_id');
            $table->unsignedBigInteger('mvr_color_id');
            $table->unsignedBigInteger('mvr_class_id');
            $table->unsignedBigInteger('mvr_model_id');
            $table->unsignedBigInteger('mvr_fuel_type_id');
            $table->unsignedBigInteger('mvr_transmission_id');
            $table->unsignedBigInteger('mvr_body_type_id');
            $table->unsignedBigInteger('agent_taxpayer_id');
            $table->string('inspection_report_path',200)->nullable();
            $table->string('certificate_of_worth_path',200)->nullable();
            $table->date('registration_date')->nullable();
            $table->unsignedBigInteger('mvr_registration_status_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('mvr_plate_size_id')->references('id')->on('mvr_plate_sizes');
            $table->foreign('mvr_vehicle_status_id')->references('id')->on('mvr_vehicle_status');
            $table->foreign('imported_from_country_id')->references('id')->on('countries');
            $table->foreign('mvr_color_id')->references('id')->on('mvr_colors');
            $table->foreign('mvr_class_id')->references('id')->on('mvr_classes');
            $table->foreign('mvr_model_id')->references('id')->on('mvr_models');
            $table->foreign('mvr_fuel_type_id')->references('id')->on('mvr_fuel_types');
            $table->foreign('mvr_transmission_id')->references('id')->on('mvr_transmission_types');
            $table->foreign('mvr_body_type_id')->references('id')->on('mvr_body_types');
            $table->foreign('mvr_registration_status_id')->references('id')->on('mvr_registration_status');
            $table->foreign('agent_taxpayer_id')->references('id')->on('taxpayers');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mvr_motor_vehicles');
    }
}
