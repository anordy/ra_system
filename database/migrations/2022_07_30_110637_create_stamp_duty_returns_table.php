<?php

use App\Enum\DisputeStatus;
use App\Enum\ReturnApplicationStatus;
use App\Enum\TaxClaimStatus;
use App\Models\Returns\ReturnStatus;
use App\Models\Returns\StampDuty\StampDutyReturn;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStampDutyReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stamp_duty_returns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('filed_by_id');
            $table->string('filed_by_type');
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('tax_type_id');
            $table->unsignedBigInteger('business_location_id');
            $table->unsignedBigInteger('financial_month_id')->nullable();
            $table->unsignedBigInteger('financial_year_id')->nullable();

            $table->integer('edited_count')->default(0);
            $table->enum('currency',['TZS', 'USD', 'BOTH'])->default('TZS');
            $table->decimal('total_amount_due', 40,2);
            $table->decimal('total_amount_due_with_penalties', 40,2);
            $table->decimal('penalty', 20, 2)->default(0);
            $table->decimal('interest', 20, 2)->default(0);
            $table->decimal('withheld_tax', 20, 2)->nullable();

            $table->dateTime('submitted_at')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->dateTime('filing_due_date')->nullable();
            $table->dateTime('payment_due_date')->nullable();

            $table->enum('status', ReturnStatus::getConstants());
            $table->enum('claim_status', TaxClaimStatus::getConstants())->default(TaxClaimStatus::NO_CLAIM);
            $table->enum('return_category', ['normal', 'debt']);
            $table->enum('application_status', ReturnApplicationStatus::getConstants());
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('business_location_id')->references('id')->on('business_locations');
            $table->foreign('business_id')->references('id')->on('businesses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stamp_duty_returns');
    }
}
