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
            $table->string('name');
            $table->bigInteger('business_type_id');
            $table->integer('tin');
            $table->text('business_activities');
            $table->date('date_of_commencing');
            $table->date('date_of_receipt');
            $table->date('effective_registration_date');
            $table->date('registration_date');
            $table->string('zrb_registration_no');
            $table->integer('vrn_no');
            $table->timestamps();

            $table->foreign('business_type_id')->references('id')->on('business_types');
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
