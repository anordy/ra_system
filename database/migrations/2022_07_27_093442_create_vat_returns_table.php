<?php

use App\Enum\DisputeStatus;
use App\Enum\ReturnApplicationStatus;
use App\Enum\TaxClaimStatus;
use App\Models\Returns\ReturnStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVatReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vat_returns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('business_location_id');
            $table->unsignedBigInteger('financial_month_id');
            $table->unsignedBigInteger('financial_year_id');
            $table->unsignedBigInteger('tax_type_id');
            $table->string('business_type')->nullable();
            $table->enum('currency',['TZS', 'USD', 'BOTH'])->default('TZS');
            $table->decimal('total_output_tax', 20,2);
            $table->decimal('total_input_tax', 20,2);
            $table->decimal('total_vat_payable', 20,2);
            $table->decimal('vat_withheld', 20,2);
            $table->decimal('infrastructure_tax', 20,2)->nullable();
            $table->decimal('total_amount_due', 20,2);
            $table->decimal('penalty', 20,2);
            $table->decimal('interest',20,2);
            $table->decimal('total_amount_due_with_penalties', 20,2);
            $table->string('has_exemption');
            $table->enum('status', ReturnStatus::getConstants());
            $table->enum('application_status', ReturnApplicationStatus::getConstants());
            $table->enum('claim_status',TaxClaimStatus::getConstants())->default(TaxClaimStatus::PENDING);
            $table->integer('editing_count',0);
            $table->string('method_used')->nullable();
            $table->unsignedBigInteger('filed_by_id');
            $table->string('filed_by_type');
            $table->dateTime('filing_due_date')->nullable();
            $table->dateTime('payment_due_date')->nullable();
            $table->dateTime('submitted_at')->nullable();
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
        Schema::dropIfExists('vat_returns');
    }
}
