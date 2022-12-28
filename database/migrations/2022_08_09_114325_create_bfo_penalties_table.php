<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBfoPenaltiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bfo_penalties', function (Blueprint $table) {
            $table->id();
            $table->string('return_id');
            $table->string('financial_month_name');
            $table->enum('currency',['TZS', 'USD'])->default('TZS');
            $table->decimal('currency_rate_in_tz', 20, 2)->default(1);
            $table->decimal('tax_amount', 20, 2);
            $table->decimal('late_filing', 20, 2);
            $table->decimal('late_payment', 20, 2);
            $table->decimal('rate_percentage', 20, 2);
            $table->decimal('rate_amount', 20, 2);
            $table->decimal('penalty_amount', 20, 2);
            $table->timestamp('start_date');
            $table->timestamp('end_date');
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
        Schema::dropIfExists('bfo_penalties');
    }
}
