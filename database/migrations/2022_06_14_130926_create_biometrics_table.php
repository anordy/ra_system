<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBiometricsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('biometrics', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->unique();
            $table->unsignedBigInteger('taxpayer_id')->nullable();
            $table->enum('hand', ['left', 'right']);
            $table->enum('finger', ['thumb', 'index', 'middle', 'ring', 'little']);
            $table->text('template')->nullable();
            $table->text('image')->nullable();

            $table->foreign('taxpayer_id')->references('id')->on('taxpayers');
            $table->softDeletes();
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
        Schema::dropIfExists('biometrics');
    }
}
