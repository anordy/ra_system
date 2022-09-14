<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessTempClosures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_temp_closures', function (Blueprint $table) {
            $table->id();
            $table->dateTime('closing_date');
            $table->dateTime('opening_date');
            $table->longText('reason');
            $table->enum('closure_type', ['all', 'location'])->default('location');
            $table->boolean('is_extended')->default(false);
            $table->boolean('show_extension')->default(false);
			$table->enum('status', ['pending', 'approved', 'rejected', 'correction', 'closed', 'temp_closed', 'deregistered', 'reopened'])->default('pending');
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('location_id')->nullable();
            $table->unsignedBigInteger('extended_from_id')->nullable();
            $table->unsignedBigInteger('submitted_by');
            $table->unsignedBigInteger('rejected_by')->nullable();
            $table->timestamp('rejected_on')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->string('marking')->nullable();
            $table->dateTime('approved_on')->nullable();
            $table->timestamps();
            $table->foreign('business_id')->references('id')->on('businesses');
            $table->foreign('approved_by')->references('id')->on('users');
            $table->foreign('rejected_by')->references('id')->on('users');

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
