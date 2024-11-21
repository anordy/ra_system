<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnOtherNatureOfBusinessOnNtrBusinessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ntr_businesses', function (Blueprint $table) {
            $table->string('other_nature_of_business')->nullable();
            $table->string('payment_gateways')->nullable();
            $table->string('website')->nullable();
            $table->unsignedBigInteger('ntr_payment_gateway_id')->nullable()->change();
        });

        Schema::table('ntr_electronic_vat_returns', function (Blueprint $table) {
            $table->boolean('is_nill')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
