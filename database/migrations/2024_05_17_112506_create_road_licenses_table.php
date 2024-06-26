<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoadLicensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('road_licenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mvr_registration_id');
            $table->unsignedBigInteger('taxpayer_id');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->integer('passengers_no')->nullable();
            $table->integer('capacity')->nullable();
            $table->timestamp('inspection_date')->nullable();
            $table->timestamp('issued_date')->nullable();
            $table->timestamp('expire_date')->nullable();
            $table->timestamp('approved_on')->nullable();
            $table->string('urn')->nullable();
            $table->string('marking')->nullable();
            $table->string('pass_mark')->nullable();
            $table->string('status')->nullable();
            $table->string('cert_auth_number')->nullable();
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
        Schema::dropIfExists('road_licenses');
    }
}
