<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnSoftDeleteToReturns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotel_returns', function (Blueprint $table) {
           // $table->softDeletes();
        });

        Schema::table('lump_sum_returns', function (Blueprint $table) {
            //$table->softDeletes();
        });

        Schema::table('petroleum_returns', function (Blueprint $table) {
           // $table->softDeletes();
        });

        Schema::table('port_returns', function (Blueprint $table) {
           // $table->softDeletes();
        });

        Schema::table('tax_returns', function (Blueprint $table) {
           // $table->softDeletes();
        });

        Schema::table('vat_returns', function (Blueprint $table) {
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
        //
    }
}
