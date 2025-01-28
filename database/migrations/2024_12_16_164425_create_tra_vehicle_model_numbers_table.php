<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTraVehicleModelNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tra_vehicle_model_numbers', function (Blueprint $table) {
            $table->id();
            $table->string('code', 5)->index('tvmn_code');
            $table->string('name', 100);
            $table->string('description', 250)->nullable();
            $table->unsignedBigInteger('tra_vehicle_make_id')->nullable();
            $table->unsignedBigInteger('tra_vehicle_model_type_id')->nullable();
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
        Schema::dropIfExists('tra_vehicle_model_numbers');
    }
}
