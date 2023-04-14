<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnSoftDeleteOnReturnItemsAndPenalties extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Return Items
        Schema::table('hotel_return_items', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('petroleum_return_items', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('port_return_items', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('vat_return_items', function (Blueprint $table) {
            $table->softDeletes();
        });


        // Penalties
        Schema::table('bfo_penalties', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('hotel_return_penalties', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('lump_sum_penalties', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('petroleum_penalties', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('port_return_penalties', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('vat_return_penalties', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('em_transaction_penalties', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('mm_transfer_penalties', function (Blueprint $table) {
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
