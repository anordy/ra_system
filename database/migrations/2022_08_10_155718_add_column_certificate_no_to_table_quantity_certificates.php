<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnCertificateNoToTableQuantityCertificates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quantity_certificates', function (Blueprint $table) {
            //
            $table->string('certificate_no')->after('id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quantity_certificates', function (Blueprint $table) {
            //
            $table->dropColumn('certificate_no');
        });
    }
}
