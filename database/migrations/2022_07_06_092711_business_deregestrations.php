<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BusinessDeregestrations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_deregistrations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');
            $table->timestamp('deregistration_date');
            $table->string('reason');
            $table->unsignedBigInteger('submitted_by');
            $table->foreign('business_id')->references('id')->on('businesses');
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
        Schema::dropIfExists('business_deregistrations');
    }
}
