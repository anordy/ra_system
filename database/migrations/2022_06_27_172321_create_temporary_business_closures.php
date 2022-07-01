<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemporaryBusinessClosures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temporary_business_closures', function (Blueprint $table) {
            $table->id();
            $table->dateTime('closing_date');
            $table->dateTime('opening_date');
            $table->string('reason');
            $table->boolean('is_extended')->default(false);
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->dateTime('approved_on')->nullable();
            $table->unsignedBigInteger('is_approved')->default(false);
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
        Schema::dropIfExists('temporary_business_closures');
    }
}
