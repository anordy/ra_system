<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnCurrencyToVatZeroRatedSales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vat_zero_rated_sales', function (Blueprint $table) {
            //
            $table->string('currency')->nullable()->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vat_zero_rated_sales', function (Blueprint $table) {
            //
            $table->dropColumn('currency');
        });
    }
}
