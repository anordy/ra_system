<?php

use App\Enum\TaxAssessmentPaymentStatus;
use App\Enum\TaxAssessmentStatus;
use App\Enum\TaxAssessmentStep;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->decimal('principal_amount', 20, 2);
            $table->decimal('interest_amount', 20, 2);
            $table->decimal('penalty_amount', 20, 2);
            $table->decimal('total_amount', 20, 2);
            $table->decimal('paid_amount', 20, 2)->default();
            $table->decimal('outstanding_amount', 20, 2)->default();
            $table->dateTime('payment_due_date')->nullable();
            $table->enum('payment_status', TaxAssessmentPaymentStatus::getConstants());
            $table->enum('app_status', TaxAssessmentStatus::getConstants())->default(TaxAssessmentStatus::ASSESSMENT);
            $table->enum('assessment_step', TaxAssessmentStep::getConstants())->default('normal')->nullable();
            $table->dateTime('paid_at')->nullable();
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
