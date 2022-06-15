<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIsic2sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('isic2s', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('description');
            $table->unsignedBigInteger('isic1_id');
            $table->timestamps();

            $table->foreign('isic1_id')->references('id')->on('isic1s');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('isic2s');
    }
}
