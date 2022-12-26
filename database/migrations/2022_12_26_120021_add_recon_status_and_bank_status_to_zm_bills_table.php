<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReconStatusAndBankStatusToZmBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('zm_bills', function (Blueprint $table) {
            $table->boolean('bank_status')->default(0);
            $table->boolean('recon_status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('zm_bills', function (Blueprint $table) {
            $table->dropColumn('bank_status');
            $table->dropColumn('recon_status');
        });
    }
}
