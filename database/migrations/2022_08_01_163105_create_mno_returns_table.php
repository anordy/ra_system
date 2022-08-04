<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMnoReturnsTable extends Migration
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
            $table->unsignedBigInteger('business_location_id');
            $table->unsignedBigInteger('filed_by');
            $table->unsignedBigInteger('financial_year_id');
            $table->unsignedBigInteger('return_month_id');
            $table->unsignedBigInteger('tax_type_id');
            $table->string('filed_type');
            $table->decimal('total',40,2);
            $table->timestamps();
            $table->softDeletes();

//            $table->foreign('business_id')->references('id')->on('businesses');
//            $table->foreign('business_location_id')->references('id')->on('business_locations');
//            $table->foreign('filed_by')->references('id')->on('taxpayers');
//            $table->foreign('financial_year_id')->references('id')->on('financial_years');
//            $table->foreign('return_month_id')->references('id')->on('return_months');
//            $table->foreign('tax_type_id')->references('id')->on('tax_types');
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
