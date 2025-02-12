<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMvrReorderPlateNumberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mvr_reorder_plate_number', function (Blueprint $table) {
            $table->id();
            $table->string('plate_number')->nullable();
            $table->string('registration_number')->nullable();
            $table->string('register_type')->nullable();
            $table->unsignedBigInteger('chassis_number_id');
            $table->unsignedBigInteger('plate_number_color_id');
            $table->unsignedBigInteger('cor_id')->nullable();
            $table->string('quantity');
            $table->string('replacement_reason');
            $table->string('is_rfid');
            $table->string('status')->default('pending');
            $table->string('mvr_plate_number_status')->nullable();
            $table->string('payment_status')->nullable();
            $table->unsignedBigInteger('mvr_registration_type_id')->nullable();
            $table->unsignedBigInteger('mvr_class_id')->nullable();
            $table->unsignedBigInteger('mvr_plate_size_id');
            $table->unsignedBigInteger('taxpayer_id');
            $table->dateTime('registered_at')->nullable();
            $table->string('marking')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('mvr_reorder_plate_number');
    }
}
