<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaseProceedingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('case_proceedings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('case_id');
            $table->date('date');
            $table->text('comment');
            $table->unsignedBigInteger('case_stage_id');
            $table->unsignedBigInteger('case_decision_id')->nullable();
            $table->timestamps();

            $table->foreign('case_stage_id')->references('id')->on('case_stages');
            $table->foreign('case_decision_id')->references('id')->on('case_decisions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('case_proceedings');
    }
}
