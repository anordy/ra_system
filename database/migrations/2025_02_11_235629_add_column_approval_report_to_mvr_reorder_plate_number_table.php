<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnApprovalReportToMvrReorderPlateNumberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mvr_reorder_plate_number', function (Blueprint $table) {
            $table->string('approval_report',255)->nullable();
            $table->string('loss_report',255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mvr_reorder_plate_number', function (Blueprint $table) {
            $table->dropColumn('approval_report');
            $table->dropColumn('loss_report');
        });
    }
}
