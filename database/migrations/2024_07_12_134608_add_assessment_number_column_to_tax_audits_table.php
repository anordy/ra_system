<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAssessmentNumberColumnToTaxAuditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tax_audits', function (Blueprint $table) {
            $table->string('assessment_number')->nullable();
        });

        Schema::table('tax_investigations', function (Blueprint $table) {
            $table->string('assessment_number')->nullable();
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
            $table->dropColumn('assessment_number');
        });
    }
}
