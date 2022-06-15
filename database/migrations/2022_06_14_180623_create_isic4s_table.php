<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIsic4sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('isic4s', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('description');
            $table->unsignedBigInteger('isic3_id');
            $table->timestamps();

            $table->foreign('isic3_id')->references('id')->on('isic3s');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('isic4s');
    }
}
