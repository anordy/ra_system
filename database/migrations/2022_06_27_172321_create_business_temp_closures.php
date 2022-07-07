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
            $table->timestamp('closing_date');
            $table->timestamp('opening_date');
            $table->string('reason');
            $table->boolean('is_extended')->default(false);
            $table->boolean('show_extension')->default(false);
			$table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('submitted_by');
            $table->unsignedBigInteger('rejected_by')->nullable();
            $table->timestamp('rejected_on')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_on')->nullable();
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
