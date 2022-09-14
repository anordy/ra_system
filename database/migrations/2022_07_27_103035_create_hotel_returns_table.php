<?php

use App\Enum\DisputeStatus;
use App\Enum\ReturnCategory;
use App\Enum\TaxClaimStatus;
use App\Models\Returns\ReturnStatus;
use App\Enum\ReturnApplicationStatus;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHotelReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotel_returns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_location_id');
            $table->unsignedBigInteger('business_id');
            $table->string('filed_by_type');
            $table->enum('currency',['TZS', 'USD', 'BOTH'])->default('TZS');
            $table->unsignedBigInteger('filed_by_id');
            $table->unsignedBigInteger('tax_type_id');
            $table->unsignedBigInteger('financial_year_id');
            $table->integer('edited_count')->default(0);
            $table->enum('status', ReturnStatus::getConstants());
            $table->enum('application_status', ReturnApplicationStatus::getConstants());
            $table->enum('claim_status',TaxClaimStatus::getConstants())->default(TaxClaimStatus::NO_CLAIM);
            $table->enum('return_category', ReturnCategory::getConstants())->default(ReturnCategory::NORMAL);
            $table->decimal('hotel_infrastructure_tax', 20, 2)->nullable();
            $table->decimal('withheld_tax', 20, 2)->nullable();
            $table->string('financial_month_id');
            $table->decimal('total_amount_due', 20, 2)->default(0);
            $table->decimal('total_amount_due_with_penalties', 20, 2)->default(0);
            $table->decimal('penalty', 20, 2)->default(0);
            $table->decimal('interest', 20, 2)->default(0);
            $table->dateTime('submitted_at')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->date('filing_due_date')->nullable();
            $table->date('payment_due_date')->nullable();
            $table->foreign('business_location_id')->references('id')->on('business_locations');
            $table->foreign('business_id')->references('id')->on('businesses');
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
        Schema::dropIfExists('hotel_returns');
    }
}
