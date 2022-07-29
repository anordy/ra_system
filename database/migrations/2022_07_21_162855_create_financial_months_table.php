<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinancialMonthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('financial_months', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('financial_year_id');
            $table->string('code');
            $table->string('name');
            $table->dateTime('start_date')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('status')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('financial_months');
    }
}
