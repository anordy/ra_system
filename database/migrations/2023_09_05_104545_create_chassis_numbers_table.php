<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChassisNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chassis_numbers', function (Blueprint $table) {
            $table->id();
            $table->string('importer_name');
            $table->string('importer_tin');
            $table->string('chassis_number')->index('chassis_number_index');
            $table->string('make');
            $table->string('year')->nullable();
            $table->string('model_number');
            $table->string('model_type');
            $table->string('body_type');
            $table->string('transmission_type');
            $table->string('vehicle_category');
            $table->integer('tare_weight');
            $table->integer('gross_weight');
            $table->string('engine_number');
            $table->integer('passenger_capacity');
            $table->string('purchase_day');
            $table->string('color');
            $table->string('fuel_type');
            $table->string('owner_category');
            $table->string('usage_type');
            $table->string('imported_from');
            $table->string('tansad_number');
            $table->integer('status')->default(1)->comment('1-For Registration, 2-For Deregistration');
            $table->string('engine_cubic_capacity')->nullable();
            $table->string('engine_kw_capacity')->nullable();
            $table->string('engine_hp_capacity')->nullable();
            $table->string('plate_number')->nullable();
            $table->string('filter_code')->nullable();
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
        Schema::dropIfExists('chassis_numbers');
    }
}
