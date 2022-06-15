<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIsic3sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('isic3s', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('description');
            $table->unsignedBigInteger('isic2_id');
            $table->timestamps();

            $table->foreign('isic2_id')->references('id')->on('isic2s');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('isic3s');
    }
}
