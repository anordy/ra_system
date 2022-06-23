<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithholdingAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withholding_agents', function (Blueprint $table) {
            $table->id();
            $table->integer('tin');
            $table->string('address');
            $table->bigInteger('wa_number')->unique();
            $table->string('institution_name');
            $table->string('institution_place');
            $table->string('email')->unique();
            $table->string('mobile')->unique();
            $table->string('position');

            $table->date('date_of_commencing');
            $table->string('title');

            $table->unsignedBigInteger('ward_id');
            $table->unsignedBigInteger('region_id');
            $table->unsignedBigInteger('district_id');
            $table->unsignedBigInteger('responsible_person_id');
            $table->unsignedBigInteger('officer_id');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('responsible_person_id')->references('id')->on('taxpayers');
            $table->foreign('ward_id')->references('id')->on('wards');
            $table->foreign('region_id')->references('id')->on('regions');
            $table->foreign('district_id')->references('id')->on('districts');
            $table->foreign('officer_id')->references('id')->on('users');



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('withholding_agents');
    }
}
