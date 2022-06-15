<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessTurnoversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_turnovers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('business_id');
            $table->integer('next_12_months');
            $table->integer('last_12_months');
            $table->timestamps();

            $table->foreign('business_id')->references('id')->on('businesses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_turnovers');
    }
}
