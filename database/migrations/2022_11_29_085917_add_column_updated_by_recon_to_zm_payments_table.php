<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnUpdatedByReconToZmPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('zm_payments', function (Blueprint $table) {
            //
            $table->boolean('created_by_recon')->default(false)->after('ctr_acc_num');
            $table->boolean('recon_trans_id')->nullable()->after('ctr_acc_num');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('zm_payments', function (Blueprint $table) {
            //
            $table->dropColumn('created_by_recon');
            $table->dropColumn('recon_trans_id');
        });
    }
}
