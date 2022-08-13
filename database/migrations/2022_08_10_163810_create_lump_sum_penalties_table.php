<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLumpSumPenaltiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lump_sum_penalties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('return_id');
            $table->string('returnMonth');
            $table->bigInteger('taxAmount');
            $table->bigInteger('lateFilingAmount')->default(0);
            $table->bigInteger('penaltyAmount');
            $table->integer('latePaymentAmount');
            $table->bigInteger('interestRate');
            $table->bigInteger('interestAmount');
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
        Schema::dropIfExists('lump_sum_penalties');
    }
}
