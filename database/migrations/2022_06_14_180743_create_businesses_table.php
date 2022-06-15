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
            $table->unsignedBigInteger('business_type_id');
            $table->integer('tin');
            $table->text('business_activities');
            $table->date('date_of_commencing');
            $table->date('date_of_receipt');
            $table->date('effective_reg_date');
            $table->date('reg_date');
            $table->string('z_no')->unique();
            $table->integer('vrn_no');
            $table->unsignedBigInteger('isic4_id');
            $table->timestamps();

            $table->foreign('business_type_id')->references('id')->on('business_types');
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
