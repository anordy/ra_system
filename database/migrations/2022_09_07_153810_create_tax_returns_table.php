<?php

use App\Enum\ApplicationStatus;
use App\Enum\ApplicationStep;
use App\Enum\PaymentMethod;
use App\Enum\ReturnCategory;
use App\Enum\ReturnStatus as EnumReturnStatus;
use App\Enum\VettingStatus;
use App\Models\Returns\ReturnStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_returns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id')->index();
            $table->unsignedBigInteger('location_id')->index();

            $table->unsignedBigInteger('return_id');
            $table->string('return_type');

            $table->unsignedBigInteger('filed_by_id');
            $table->string('filed_by_type');

            $table->unsignedBigInteger('tax_type_id')->index();
            $table->unsignedBigInteger('financial_month_id')->index();

            $table->enum('currency', ['TZS', 'USD']);

            $table->decimal('principal', 20, 2);
            $table->decimal('interest', 20, 2);
            $table->decimal('penalty', 20, 2);

            $table->decimal('infrastructure', 20, 2)->nullable();

            // Seaport & airport
            $table->decimal('airport_safety_fee', 20, 2)->nullable();
            $table->decimal('airport_service_charge', 20, 2)->nullable();
            $table->decimal('seaport_service_charge', 20, 2)->nullable();
            $table->decimal('seaport_transport_charge', 20, 2)->nullable();
            $table->decimal('infrastructure_znz_znz', 20, 2)->nullable();
            $table->decimal('infrastructure_znz_tz', 20, 2)->nullable();

            // Petroleum
            $table->decimal('rdf_fee', 20, 2)->nullable();
            $table->decimal('road_license_fee', 20, 2)->nullable();

            $table->decimal('withheld_tax', 20, 2)->nullable();
            $table->decimal('credit_brought_forward', 20, 2)->nullable();

            $table->decimal('total_amount', 20, 2)->nullable();
            $table->decimal('outstanding_amount', 20, 2)->nullable();

            $table->unsignedBigInteger('lumpsum_quarter')->nullable();

            $table->boolean('has_claim')->default(false);
            $table->boolean('is_nill')->default(false);

            $table->enum('filing_method', ['normal', 'method_one', 'method_two'])->default('normal');
            $table->enum('return_status', EnumReturnStatus::getConstants())->default('submitted');
            $table->enum('payment_status', ReturnStatus::getConstants())->default('submitted');
            $table->enum('return_category', ReturnCategory::getConstants())->default('normal');

            $table->enum('application_step', ApplicationStep::getConstants())->default('filing');
            $table->enum('application_status', ApplicationStatus::getConstants())->default('normal');
            $table->enum('payment_method', PaymentMethod::getConstants())->nullable();

            $table->timestamp('paid_at')->nullable();
            $table->timestamp('payment_due_date');
            $table->timestamp('filing_due_date');

            $table->timestamp('curr_payment_due_date')->nullable();
            $table->timestamp('curr_filing_due_date');

            $table->boolean('failed_verification')->default(0);
            $table->text('ci_payload', 4000)->nullable();

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
        Schema::dropIfExists('tax_returns');
    }
}
