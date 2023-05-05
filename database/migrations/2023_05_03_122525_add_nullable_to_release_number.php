<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNullableToReleaseNumber extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vat_return_supplier_details', function (Blueprint $table) {
            $table->string('release_number')->nullable()->change();
        });
        Schema::table('vat_mainland_purchases', function (Blueprint $table) {
            $table->string('release_number')->nullable()->change();
        });
        Schema::table('vat_local_purchases', function (Blueprint $table) {
            $table->string('release_number')->nullable()->change();
        });
        Schema::table('local_purchase_details', function (Blueprint $table) {
            $table->string('release_number')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
