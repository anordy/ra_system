<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnMonthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('return_months', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->dateTime('start_date');
            $table->dateTime('due_date');
            $table->unsignedBigInteger('financial_year_id');
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
        Schema::dropIfExists('return_months');
    }
}
