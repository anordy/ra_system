<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenaltyRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penalty_rates', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->decimal('rate', 20, 4);
            $table->unsignedBigInteger('financial_year_id');
            $table->boolean('is_approved')->default(0);
            $table->boolean('is_updated')->default(0);
            $table->timestamps();
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
        Schema::dropIfExists('penalty_rates');
    }
}
