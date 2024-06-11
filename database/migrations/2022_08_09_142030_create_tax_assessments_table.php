<?php

use App\Enum\PaymentMethod;
use App\Enum\ApplicationStatus;
use App\Enum\TaxAssessmentStep;
use App\Enum\TaxAssessmentStatus;
use Illuminate\Support\Facades\Schema;
use App\Enum\TaxAssessmentPaymentStatus;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaxAssessmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_assessments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('tax_type_id');
            $table->unsignedBigInteger('assessment_id');
            $table->enum('currency', ['TZS', 'USD', 'BOTH'])->default('TZS');
            $table->string('assessment_type');
            // Original amounts
            $table->decimal('original_principal_amount', 20, 2);
            $table->decimal('original_interest_amount', 20, 2);
            $table->decimal('original_penalty_amount', 20, 2);
            $table->decimal('original_total_amount', 20, 2);
            $table->decimal('paid_amount', 20, 2)->default(0);

            $table->decimal('interest_amount', 20, 2);
            $table->decimal('penalty_amount', 20, 2);
            $table->decimal('principal_amount', 20, 2);

            $table->enum('application_status', ApplicationStatus::getConstants())->default('normal');
            $table->enum('payment_method', PaymentMethod::getConstants())->nullable();

            $table->decimal('total_amount', 20, 2);
            $table->decimal('outstanding_amount', 20, 2)->default(0);
            $table->timestamp('payment_due_date')->nullable();
            $table->timestamp('curr_payment_due_date')->nullable();

            $table->enum('payment_status', TaxAssessmentPaymentStatus::getConstants())->default(TaxAssessmentPaymentStatus::DRAFT);
            $table->enum('app_status', TaxAssessmentStatus::getConstants())->default(TaxAssessmentStatus::ASSESSMENT);
            $table->enum('assessment_step', TaxAssessmentStep::getConstants())->default('normal')->nullable();
            $table->timestamp('paid_at')->nullable();
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
        Schema::dropIfExists('tax_assessments');
    }
}
