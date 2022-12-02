<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMvrPlateNumberCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mvr_plate_number_collections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mvr_registration_id');
            $table->date('collection_date');
            $table->string('collector_name',100);
            $table->string('collector_phone',20);
            $table->timestamps();

            $table->foreign('mvr_registration_id','mvr_pnc_reg')->references('id')->on('mvr_vehicle_registration');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mvr_plate_number_collections');
    }
}
