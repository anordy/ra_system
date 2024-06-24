<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToTaxAuditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tax_audits', function (Blueprint $table) {
            $table->string('notification_letter')->nullable();
            $table->date('new_audit_date')->nullable();
            $table->text('extension_reason')->nullable();
            $table->string('entry_minutes')->nullable();
            $table->date('preliminary_report_date')->nullable();
            $table->date('notification_letter_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tax_audits', function (Blueprint $table) {
            $table->dropColumn('notification_letter');
            $table->dropColumn('new_audit_date');
            $table->dropColumn('extension_reason');
            $table->dropColumn('entry_minutes');
            $table->dropColumn('preliminary_report_date');
            $table->dropColumn('notification_letter_date');
        });
    }
}
