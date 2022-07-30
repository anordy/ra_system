<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMNOReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mno_returns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('taxpayer_id');
            $table->unsignedBigInteger('financial_year_id');
            $table->unsignedBigInteger('return_month_id');
            $table->decimal('total',40,2);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('business_id')->references('id')->on('businesses');
            $table->foreign('location_id')->references('id')->on('business_locations');
            $table->foreign('taxpayer_id')->references('id')->on('taxpayers');
            $table->foreign('financial_year_id')->references('id')->on('financial_years');
            $table->foreign('return_month_id')->references('id')->on('return_months');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mno_returns');
    }
}
