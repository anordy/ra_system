<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreColumnsToTaxAuditTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tax_audits', function (Blueprint $table) {
            $table->string('audit_date_extension_attachment')->nullable();
            $table->string('preliminary_extension_attachment')->nullable();
            $table->boolean('forwarded_to_investigation')->default(false);
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
            $table->dropColumn('audit_date_extension_attachment');
            $table->dropColumn('preliminary_extension_attachment');
            $table->dropColumn('forwarded_to_investigation');
        });
    }
}
