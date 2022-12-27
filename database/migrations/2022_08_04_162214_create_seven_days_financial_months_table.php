<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSevenDaysFinancialMonthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seven_days_financial_months', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('financial_year_id');
            $table->string('name');
            $table->enum('number', [1,2,3,4,5,6,7,8,9,10,11,12]);
            $table->dateTime('due_date');
            $table->boolean('is_approved')->default(0);
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
        Schema::dropIfExists('seven_days_financial_months');
    }
}
