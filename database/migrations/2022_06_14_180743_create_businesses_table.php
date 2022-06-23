<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_type_id'); // Sole / Partner / Company
            $table->unsignedBigInteger('business_activities_type_id'); // Wholesale or Retail
            $table->unsignedBigInteger('currency_id'); // Wholesale or Retail

            $table->string('name');
            $table->string('tin');
            $table->string('reg_no');
            $table->string('owner_designation');
            $table->string('mobile');
            $table->string('alt_mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('place_of_business');
            $table->string('physical_address');
            $table->dateTime('date_of_commencing');
            $table->string('pre_estimated_turnover');
            $table->string('post_estimated_turnover');
            $table->string('goods_and_services_types');
            $table->string('goods_and_services_example');


            // Contact person
            $table->unsignedBigInteger('contact_person_id')->nullable();

            // Tax Filling Person
            $table->unsignedBigInteger('tax_filling_person_id')->nullable();

            $table->dateTime('date_of_receipt')->nullable();
            $table->dateTime('effective_reg_date')->nullable();
            $table->dateTime('reg_date')->nullable();
            $table->string('z_no')->nullable();
            $table->integer('vrn_no')->nullable();
            $table->unsignedBigInteger('isic4_id')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('business_category_id')->references('id')->on('business_categories');
            $table->foreign('isic4_id')->references('id')->on('isic4s');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('businesses');
    }
}
