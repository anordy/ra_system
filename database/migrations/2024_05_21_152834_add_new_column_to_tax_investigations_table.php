<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnToTaxInvestigationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tax_investigations', function (Blueprint $table) {
            $table->string('case_number')->nullable();
            $table->string('notice_of_discussion')->nullable();
            $table->string('preliminary_report')->nullable();
            $table->string('final_report')->nullable();
            $table->text('extension_reason')->nullable();
            $table->boolean('was_rejected')->nullable()->default(0);
            $table->boolean('has_preliminary_extension')->nullable()->default(0);
            $table->text('rejection_reason')->nullable();
            $table->date('extension_date')->nullable();
            $table->date('preliminary_report_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tax_investigations', function (Blueprint $table) {
            $table->dropColumn('case_number');
            $table->dropColumn('notice_of_discussion');
            $table->dropColumn('extension_date');
            $table->dropColumn('final_report');
            $table->dropColumn('extension_reason');
            $table->dropColumn('was_rejected');
            $table->dropColumn('has_preliminary_extension');
            $table->dropColumn('rejection_reason');
            $table->dropColumn('preliminary_report');
            $table->dropColumn('preliminary_report_date');
        });
    }
}
