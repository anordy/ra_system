<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLandLeasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('land_leases', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_registered');
            $table->unsignedBigInteger('taxpayer_id')->nullable();
            $table->string('dp_number');
            $table->date('commence_date');
            $table->string('payment_month');
            $table->decimal('payment_amount', 10, 2);
            $table->integer('review_schedule');
            $table->integer('valid_period_term');
            $table->enum('status',['submitted', 'control-number-generating', 'control-number-generated', 'control-number-generating-failed', 'paid-partially', 'complete'])->default('submitted');
            $table->unsignedBigInteger('region_id');
            $table->unsignedBigInteger('district_id');
            $table->unsignedBigInteger('ward_id');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('edited_by')->nullable();
            $table->enum('category', ['sole owner','business']);
            $table->unsignedBigInteger('business_location_id')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('lease_agreement_path');
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
        Schema::dropIfExists('land_leases');
    }
}
