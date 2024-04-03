<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaxVerificationRiskIndicatorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_verification_risk_indicator', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('verification_id');
            $table->unsignedBigInteger('risk_indicator_id');
            $table->timestamps();

            $table->foreign('verification_id')->references('id')->on('tax_verifications')->onDelete('cascade');
            $table->foreign('risk_indicator_id')->references('id')->on('returns_risk_indicators')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tax_verification_risk_indicator');
    }
}
