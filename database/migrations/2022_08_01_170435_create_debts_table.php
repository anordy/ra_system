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
            $table->string('debt_type');
            $table->unsignedBigInteger('debt_type_id');
            $table->unsignedBigInteger('tax_type_id');
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('business_location_id');
            $table->string('currency')->nullable();
            $table->decimal('original_principal_amount', 20,2);
            $table->decimal('original_penalty', 20,2);
            $table->decimal('original_interest', 20,2);
            $table->decimal('original_total_amount', 20,2); 
            $table->decimal('principal_amount', 20,2);
            $table->decimal('penalty', 20,2);
            $table->decimal('interest', 20,2);
            $table->decimal('total_amount', 20,2);
            $table->decimal('outstanding_amount', 20,2);
            $table->dateTime('logged_date');
            $table->dateTime('submitted_at');
            $table->dateTime('filing_due_date')->nullable();
            $table->dateTime('last_due_date')->nullable();
            $table->dateTime('curr_due_date')->nullable();
            $table->integer('demand_notice_count')->nullable();
            $table->enum('app_step', ['waiver', 'extension', 'normal'])->default('normal');
            $table->enum('origin', ['job', 'manual'])->nullable();
            $table->unique(['debt_type', 'debt_type_id'], 'debt_type_unique');
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
