<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_infos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('business_activities_type_id'); // Wholesale or Retail
            $table->unsignedBigInteger('currency_id');

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
            $table->unsignedBigInteger('responsible_person_id')->nullable();

            // Tax Filling Person
            $table->boolean('is_own_consultant')->default(true);

            // Not sure
            $table->dateTime('reg_date')->nullable();
            $table->string('z_no')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('business_infos');
    }
}
