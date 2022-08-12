<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePortReturnPenaltiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('port_return_penalties', function (Blueprint $table) {
            $table->id();
            $table->string('return_id');
            $table->string('currency');
            $table->string('financial_month_name');
            $table->decimal('tax_amount', 20, 2);
            $table->decimal('late_filing', 20, 2);
            $table->decimal('late_payment', 20, 2);
            $table->decimal('rate_percentage', 20, 2);
            $table->decimal('rate_amount', 20, 2);
            $table->decimal('penalty_amount', 20, 2);
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
        Schema::dropIfExists('port_return_penalties');
    }
}
