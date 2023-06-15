<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVfmsBusinessUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vfms_business_units', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('unit_id');
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('locality_id');
            $table->unsignedBigInteger('zidras_tax_type_id');
            $table->string('unit_name');
            $table->string('vfms_tax_type');
            $table->string('znumber');
            $table->string('street');
            $table->string('business_name')->nullable();
            $table->string('trade_name')->nullable();
            $table->string('tax_office')->nullable();
            $table->boolean('is_headquarter')->default(false);
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
        Schema::dropIfExists('vfms_business_units');
    }
}
