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
            $table->unsignedBigInteger('location_id')->nullable();
            $table->unsignedBigInteger('tax_audit_id')->nullable();
            $table->unsignedBigInteger('new_headquarter_id')->nullable();
            $table->timestamp('deregistration_date');
            $table->enum('deregistration_type', ['all', 'location'])->default('location');
			$table->enum('status', ['pending', 'approved', 'rejected', 'correction',  'closed', 'temp_closed', 'deregistered'])->default('pending');
            $table->longText('reason');
            $table->unsignedBigInteger('submitted_by');
            $table->unsignedBigInteger('rejected_by')->nullable();
            $table->timestamp('rejected_on')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->string('marking')->nullable();
            $table->timestamp('approved_on')->nullable();
            $table->foreign('business_id')->references('id')->on('businesses');
            $table->foreign('approved_by')->references('id')->on('users');
            $table->foreign('rejected_by')->references('id')->on('users');
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
