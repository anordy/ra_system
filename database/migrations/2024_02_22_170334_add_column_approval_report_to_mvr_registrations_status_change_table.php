<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnApprovalReportToMvrRegistrationsStatusChangeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mvr_registrations_status_change', function (Blueprint $table) {
            $table->string('approval_report')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mvr_registrations_status_change', function (Blueprint $table) {
            $table->dropColumn('approval_report');
        });
    }
}
