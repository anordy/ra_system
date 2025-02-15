<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnTaxpayerEvidenceToMvrDeregistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mvr_deregistrations', function (Blueprint $table) {
            $table->string('taxpayer_evidence')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mvr_deregistrations', function (Blueprint $table) {
            $table->dropColumn('taxpayer_evidence');
        });
    }
}