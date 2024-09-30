<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnReasonToTaxVerificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tax_verifications', function (Blueprint $table) {
            $table->string('initiation_reason', 2000)->nullable();
            $table->string('notification_letter', 255)->nullable();
            $table->string('final_report', 255)->nullable();
            $table->string('notice_of_discussion', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tax_verifications', function (Blueprint $table) {
            //
        });
    }
}
