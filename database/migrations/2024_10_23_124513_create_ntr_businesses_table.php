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
            $table->unsignedBigInteger('ntr_business_category_id')->nullable();
            $table->string('other_category')->nullable();
            $table->integer('ownership_type')->nullable();
            $table->integer('business_type');
            $table->string('entity_type')->nullable();
            $table->string('name');
            $table->string('business_address')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('incorporation_country_id')->nullable();
            $table->unsignedBigInteger('ntr_business_nature_id')->nullable();
            $table->unsignedBigInteger('ntr_payment_gateway_id')->nullable();
            $table->unsignedBigInteger('ntr_taxpayer_id')->index('ntr_businesses_taxpayer_id');
            $table->string('street')->nullable();
            $table->string('status')->nullable();
            $table->string('vrn')->nullable();
            $table->string('ztn_number')->nullable();
            $table->string('ztn_location_number')->nullable();
            $table->string('email')->unique()->nullable();
            $table->decimal('annual_revenue_threshold', 20, 2)->nullable();
            $table->string('other_nature_of_business')->nullable();
            $table->string('payment_gateways')->nullable();
            $table->string('website')->nullable();
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
