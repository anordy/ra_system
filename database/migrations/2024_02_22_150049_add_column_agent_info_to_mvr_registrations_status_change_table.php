<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnAgentInfoToMvrRegistrationsStatusChangeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mvr_registrations_status_change', function (Blueprint $table) {
            $table->unsignedBigInteger('current_registration_id')->nullable();
            $table->boolean('is_agent_registration')->default(false)->nullable();
            $table->string('registrant_tin')->nullable();
            $table->boolean('use_company_name')->default(false)->nullable();
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
            $table->dropColumn('current_registration_id');
            $table->dropColumn('is_agent_registration');
            $table->dropColumn('registrant_tin');
            $table->dropColumn('use_company_name');
        });
    }
}
