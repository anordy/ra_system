<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRioRegisterOffences extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rio_register_offences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rio_offence_id');
            $table->unsignedBigInteger('rio_register_id');
            $table->timestamps();

            $table->foreign('rio_offence_id')->references('id')->on('rio_offences');
            $table->foreign('rio_register_id')->references('id')->on('rio_register');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rio_register_offences');
    }
}
