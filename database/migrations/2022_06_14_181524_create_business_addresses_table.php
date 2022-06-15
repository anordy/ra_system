<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_addresses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('business_id');
            $table->string('po_box');
            $table->integer('house_no');
            $table->string('street');
            $table->bigInteger('ward_id');
            $table->string('phone_no');
            $table->string('alt_phone_no')->nullable();
            $table->string('email');
            $table->timestamps();

            $table->foreign('business_id')->references('id')->on('businesses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_addresses');
    }
}
