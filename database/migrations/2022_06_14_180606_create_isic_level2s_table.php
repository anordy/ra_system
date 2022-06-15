<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIsicLevel2sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('isic_level2s', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('description');
            $table->bigInteger('isic_level1_id');
            $table->timestamps();

            $table->foreign('isic_level1_id')->references('id')->on('isic_level1s');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('isic_level2s');
    }
}
