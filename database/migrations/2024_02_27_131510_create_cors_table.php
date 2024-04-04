<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cors', function (Blueprint $table) {
            $table->id();
            $table->string('cor');
            $table->string('make')->nullable();
            $table->string('model')->nullable();
            $table->string('year')->nullable();
            $table->string('chassis_number')->nullable();
            $table->string('inspected_mileage')->nullable();
            $table->string('capacity')->nullable();
            $table->string('engine')->nullable();
            $table->string('body_type')->nullable();
            $table->string('inspected_date')->nullable();
            $table->string('color')->nullable();
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
        Schema::dropIfExists('cors');
    }
}
