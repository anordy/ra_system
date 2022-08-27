<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaseAppealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('case_appeals', function (Blueprint $table) {
            $table->id();
            $table->string('appeal_number',20);
            $table->unsignedBigInteger('case_id');
            $table->text('appeal_details');
            $table->date('date_opened');
            $table->unsignedBigInteger('case_outcome_id')->nullable();
            $table->unsignedBigInteger('court_level_id')->nullable();
            $table->date('date_closed')->nullable();
            $table->text('outcome_details')->nullable();
            $table->timestamps();

            $table->foreign('case_outcome_id')->references('id')->on('case_outcomes');
            $table->foreign('case_id')->references('id')->on('cases');
            $table->foreign('court_level_id')->references('id')->on('court_levels');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('case_appeals');
    }
}
