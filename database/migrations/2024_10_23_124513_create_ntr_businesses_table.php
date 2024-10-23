<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNtrBusinessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ntr_businesses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ntr_business_category_id');
            $table->string('other_category')->nullable();
            $table->integer('ownership_type');
            $table->string('individual_first_name')->nullable();
            $table->string('individual_middle_name')->nullable();
            $table->string('individual_last_name')->nullable();
            $table->string('individual_position')->nullable();
            $table->string('individual_address')->nullable();
            $table->string('entity_type')->nullable();
            $table->string('name');
            $table->string('nature_of_business');
            $table->string('business_address');
            $table->unsignedBigInteger('country_id');
            $table->unsignedBigInteger('ntr_taxpayer_id')->index('ntr_businesses_taxpayer_id');
            $table->string('street');
            $table->string('status')->nullable();
            $table->string('vrn')->nullable();
            $table->string('ztn_number')->nullable();
            $table->string('ztn_location_number')->nullable();
            $table->string('email')->unique()->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ntr_businesses');
    }
}
