<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTancisChassisNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tancis_chassis_numbers', function (Blueprint $table) {
            $table->id();
            $table->string('tansad_number');
            $table->string('importer_tin');
            $table->string('importer_name');
            $table->string('chassis_number')->unique()->index('tcn_chassis');
            $table->string('make')->nullable();
            $table->string('model_number')->nullable();
            $table->string('model_type')->nullable();
            $table->string('body_type')->nullable();
            $table->string('transmission_type')->nullable();
            $table->string('vehicle_category')->nullable();
            $table->decimal('tare_weight', 10, 2)->nullable();
            $table->decimal('gross_weight', 10, 2)->nullable();
            $table->string('engine_number')->nullable();
            $table->integer('engine_capacity')->nullable();
            $table->integer('passenger_capacity')->nullable();
            $table->string('purchase_day')->nullable();
            $table->integer('vehicle_manufacture_year')->nullable();
            $table->string('vehicle_color')->nullable();
            $table->string('fuel_type')->nullable();
            $table->string('owner_category')->nullable();
            $table->string('usage_type')->nullable();
            $table->string('imported_from')->nullable();
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
        Schema::dropIfExists('tancis_chassis_numbers');
    }
}
