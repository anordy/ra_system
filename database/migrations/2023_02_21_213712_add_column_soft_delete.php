<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnSoftDelete extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tax_agent_professionals', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('tax_agent_academic_qualifications', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('tax_agent_tr_experiences', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('tax_agents', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('transaction_fees', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('debt_recovery_measures', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('workflow_task_operators', function (Blueprint $table) {
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
