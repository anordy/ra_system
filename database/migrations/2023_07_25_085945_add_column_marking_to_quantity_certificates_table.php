<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnMarkingToQuantityCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quantity_certificates', function (Blueprint $table) {
            $table->string('marking')->nullable()->after('status');
            $table->string('quantity_certificate_attachment')->nullable()->after('status');
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
        });
    }
}
