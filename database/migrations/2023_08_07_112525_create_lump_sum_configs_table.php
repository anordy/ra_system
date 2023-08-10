<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLumpSumConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lump_sum_configs', function (Blueprint $table) {
            $table->id();
            $table->decimal('min_sales_per_year', 12, 2);
            $table->decimal('max_sales_per_year', 12, 2);
            $table->decimal('payments_per_year', 12, 2);
            $table->decimal('payments_per_installment', 12, 2);
            $table->decimal('payments_interval_in_moths')->default(3);
            $table->boolean('value_calculated')->default(false);
            $table->enum('col_type',['total', 'subtotal','normal'])->default('normal');
            $table->enum('currency',['TZS', 'USD', 'BOTH'])->default('TZS');
            $table->boolean('active')->default(true);
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
        Schema::dropIfExists('lump_sum_configs');
    }
}
