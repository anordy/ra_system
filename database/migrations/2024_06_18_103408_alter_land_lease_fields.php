<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterLandLeaseFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('land_leases', function (Blueprint $table) {
            $table->boolean('is_registered')->nullable()->change();
            $table->string('dp_number')->nullable()->change();
            $table->date('commence_date')->nullable()->change();
            $table->date('rent_commence_date')->nullable()->change();
            $table->string('payment_month')->nullable()->change();
            $table->decimal('payment_amount', 10, 2)->nullable()->change();
            $table->integer('review_schedule')->nullable()->change();
            $table->integer('valid_period_term')->nullable()->change();
            $table->unsignedBigInteger('region_id')->nullable()->change();
            $table->unsignedBigInteger('district_id')->nullable()->change();
            $table->unsignedBigInteger('ward_id')->nullable()->change();
            $table->unsignedBigInteger('created_by')->nullable()->change();
            $table->string('category')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
