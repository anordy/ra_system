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
            $table->boolean('show_extension')->default(false);
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->unsignedBigInteger('rejected_by')->nullable();
            $table->dateTime('approved_on')->nullable();
            $table->dateTime('rejected_on')->nullable();
            // $table->string('rejected_reason');
            $table->enum('status', ['pending', 'approved', 'rejected']);
            $table->timestamps();
            $table->foreign('business_id')->references('id')->on('businesses');
            $table->foreign('rejected_by')->references('id')->on('users');
            $table->foreign('approved_by')->references('id')->on('users');

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
