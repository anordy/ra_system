<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cases', function (Blueprint $table) {
            $table->id();
            $table->date('date_opened');
            $table->string('case_number');
            $table->string('court');
            $table->text('case_details');
            $table->unsignedBigInteger('tax_investigation_id');
            $table->unsignedBigInteger('case_stage_id');
            $table->unsignedBigInteger('case_outcome_id')->nullable();
            $table->unsignedBigInteger('assigned_officer_id')->nullable();
            $table->date('date_closed')->nullable();
            $table->timestamps();

            $table->foreign('tax_investigation_id')->references('id')->on('tax_investigations');
            $table->foreign('case_stage_id')->references('id')->on('case_stages');
            $table->foreign('case_outcome_id')->references('id')->on('case_outcomes');
            $table->foreign('assigned_officer_id')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cases');
    }
}
