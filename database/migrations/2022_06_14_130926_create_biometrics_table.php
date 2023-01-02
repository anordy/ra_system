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
            $table->string('reference_no')->nullable();
            $table->unsignedBigInteger('taxpayer_id')->nullable();
            $table->enum('hand', ['left', 'right']);
            $table->enum('finger', ['thumb', 'index', 'middle', 'ring', 'little']);
            $table->string('template', 4000)->nullable();
            $table->longText('image', 4000)->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->foreign('taxpayer_id')->references('id')->on('taxpayers');
            $table->foreign('approved_by')->references('id')->on('users');
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
