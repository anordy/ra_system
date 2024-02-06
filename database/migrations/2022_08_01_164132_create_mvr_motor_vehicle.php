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
            $table->string('chassis_number',20);
            $table->unsignedBigInteger('agent_taxpayer_id')->nullable();
            $table->string('inspection_report_path',200)->nullable();
            $table->string('certificate_of_worth_path',200)->nullable();
            $table->date('inspection_date')->nullable();
            $table->string('certificate_number',30)->nullable();
            $table->date('registration_date')->useCurrent();
            $table->integer('mileage')->nullable();
            $table->unsignedBigInteger('mvr_registration_status_id');
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
        Schema::dropIfExists('mvr_motor_vehicles');
    }
}
