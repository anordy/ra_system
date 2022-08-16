<?php

use App\Enum\PaymentMethod;
use App\Enum\TaxAssessmentPaymentStatus;
use App\Enum\TaxAssessmentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxAssessmentHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_assessment_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tax_assessment_id');
            $table->decimal('principal_amount', 20, 2);
            $table->decimal('interest_amount', 20, 2);
            $table->decimal('penalty_amount', 20, 2);
            $table->decimal('total_amount', 20, 2);
            $table->dateTime('payment_due_date')->nullable();
            $table->decimal('paid_amount', 20, 2);
            $table->enum('app_status', TaxAssessmentStatus::getConstants())->default(TaxAssessmentStatus::ASSESSMENT);
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
        Schema::dropIfExists('tax_assessment_histories');
    }
}
