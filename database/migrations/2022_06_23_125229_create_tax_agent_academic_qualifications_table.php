<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxAgentAcademicQualificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_agent_academic_qualifications', function (Blueprint $table) {
            $table->id();
			$table->string('school_name');
			$table->date('from');
			$table->date('to');
			$table->string('examining_body');
			$table->integer('division_id');
			$table->text('attachment')->nullable();
			$table->unsignedBigInteger('tax_agent_id');
			$table->foreign('tax_agent_id')->references('id')->on('tax_agents');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tax_agent_academic_qualifications');
    }
}
