<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

<<<<<<<< HEAD:database/migrations/2025_02_15_173745_add_column_taxpayer_evidence_to_mvr_deregistrations_table.php
class AddColumnTaxpayerEvidenceToMvrDeregistrationsTable extends Migration
========
class AddColumnNameToMvrColorsTable extends Migration
>>>>>>>> 874648ce0 (ref: mvr & dl license):database/migrations/2025_02_14_120250_add_column_name_to_mvr_colors_table.php
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
<<<<<<<< HEAD:database/migrations/2025_02_15_173745_add_column_taxpayer_evidence_to_mvr_deregistrations_table.php
        Schema::table('mvr_deregistrations', function (Blueprint $table) {
            $table->string('taxpayer_evidence')->nullable();
========
        Schema::table('mvr_colors', function (Blueprint $table) {
            $table->string('name')->nullable();
>>>>>>>> 874648ce0 (ref: mvr & dl license):database/migrations/2025_02_14_120250_add_column_name_to_mvr_colors_table.php
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
<<<<<<<< HEAD:database/migrations/2025_02_15_173745_add_column_taxpayer_evidence_to_mvr_deregistrations_table.php
        Schema::table('mvr_deregistrations', function (Blueprint $table) {
            $table->dropColumn('taxpayer_evidence');
========
        Schema::table('mvr_colors', function (Blueprint $table) {
            //
>>>>>>>> 874648ce0 (ref: mvr & dl license):database/migrations/2025_02_14_120250_add_column_name_to_mvr_colors_table.php
        });
    }
}