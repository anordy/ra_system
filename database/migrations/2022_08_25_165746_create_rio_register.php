<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRioRegister extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rio_register', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dl_drivers_license_owner_id');
            $table->unsignedBigInteger('mvr_motor_vehicle_registration_id');
            $table->date('date');
            $table->enum('block_type',['NONE','LICENSE','PLATE NUMBER'])->default('NONE');
            $table->enum('block_status',['ACTIVE','REMOVED'])->nullable();
            $table->date('block_removed_at')->nullable();
            $table->unsignedBigInteger('block_removed_by')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('dl_drivers_license_owner_id')->references('id')->on('dl_drivers_license_owners');
            $table->foreign('mvr_motor_vehicle_registration_id')->references('id')->on('mvr_vehicle_registration');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('block_removed_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rio_register');
    }
}
