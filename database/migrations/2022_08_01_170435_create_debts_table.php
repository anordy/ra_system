<?php

use App\Enum\DebtPaymentMethod;
use App\Enum\RecoveryMeasureStatus;
use App\Models\Returns\ReturnStatus;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->unsignedBigInteger('debt_id');
            $table->unsignedBigInteger('tax_type_id');
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('business_location_id');
            $table->string('currency')->nullable();
            $table->string('marking')->nullable();
            $table->decimal('original_principal_amount', 20,2);
            $table->decimal('original_penalty', 20,2);
            $table->decimal('original_interest', 20,2);
            $table->decimal('original_total_amount', 20,2); 
            $table->decimal('principal_amount', 20,2);
            $table->decimal('penalty', 20,2);
            $table->decimal('interest', 20,2);
            $table->decimal('total_amount', 20,2);
            $table->decimal('outstanding_amount', 20,2);
            $table->timestamp('logged_date');
            $table->timestamp('submitted_at');
            $table->timestamp('filing_due_date')->nullable();
            $table->timestamp('last_due_date')->nullable();
            $table->timestamp('curr_due_date')->nullable();
            $table->timestamp('approved_on')->nullable();
            $table->integer('demand_notice_count')->nullable()->default(0);
            $table->timestamp('next_demand_notice_date')->nullable();
            $table->enum('app_step', ['waiver', 'extension', 'normal'])->default('normal');
            $table->enum('status', ReturnStatus::getConstants())->default('submitted');
            $table->enum('payment_method', DebtPaymentMethod::getConstants())->default(DebtPaymentMethod::NORMAL);
            $table->enum('recovery_measure_status', RecoveryMeasureStatus::getConstants())->default(RecoveryMeasureStatus::NONE);
            $table->enum('origin', ['job', 'manual'])->nullable();
            $table->unique(['debt_type', 'debt_id'], 'debt_type_unique');
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
