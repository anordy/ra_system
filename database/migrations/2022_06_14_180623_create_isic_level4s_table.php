<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIsicLevel4sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('isic_level4s', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('description');
            $table->bigInteger('isic_level3_id');
            $table->timestamps();

            $table->foreign('isic_level3_id')->references('id')->on('isic_level3s');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('isic_level4s');
    }
}
