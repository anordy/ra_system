<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNullablesToVatCashSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vat_cash_sales', function (Blueprint $table) {
            $table->string('purchases_type')->nullable()->change();
            $table->string('document')->nullable()->change();
            $table->integer('from_number')->nullable()->change();
            $table->integer('to_number')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vat_cash_sales', function (Blueprint $table) {
            //
        });
    }
}
