<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDebtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('debts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tax_type_id');
            $table->string('debt_type');
            $table->unsignedBigInteger('debt_type_id');
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('location_id');
            $table->decimal('original_principal_amount', 20,2);
            $table->decimal('original_penalty', 20,2);
            $table->decimal('original_interest', 20,2);
            $table->decimal('original_total_amount', 20,2); 
            $table->decimal('principal_amount', 20,2);
            $table->decimal('penalty', 20,2);
            $table->decimal('interest', 20,2);
            $table->decimal('total_amount', 20,2);
            $table->decimal('outstanding_amount', 20,2);
            $table->decimal('logged_date', 20,2);
            $table->dateTime('last_due_date')->nullable();
            $table->dateTime('curr_due_date')->nullable();
            $table->integer('demand_notice_count')->nullable();
            $table->enum('app_step', ['waiver', 'extension', 'normal'])->default('normal');
            $table->enum('origin', ['job', 'manual'])->nullable();
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
        Schema::dropIfExists('debts');
    }
}
