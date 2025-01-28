<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTraVehicleModelTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tra_vehicle_model_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 5)->index('tvmt_code');
            $table->string('name', 100);
            $table->string('description', 250)->nullable();
            $table->unsignedBigInteger('tra_vehicle_make_id')->nullable();
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
        Schema::dropIfExists('tra_vehicle_model_types');
    }
}
