<?php

use App\Enum\DisputeStatus;
use App\Models\Returns\ReturnStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePortReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('port_returns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_location_id');
            $table->unsignedBigInteger('business_id');
            $table->string('filed_by_type');
            $table->unsignedBigInteger('filed_by_id');
            $table->unsignedBigInteger('tax_type_id');
            $table->unsignedBigInteger('financial_year_id');
            $table->integer('edited_count')->default(0);
            $table->foreign('business_location_id')->references('id')->on('business_locations');
            $table->foreign('business_id')->references('id')->on('businesses');
            $table->foreign('financial_year_id')->references('id')->on('financial_years');
            $table->unsignedBigInteger('financial_month_id');
            $table->decimal('total_vat_payable_tzs', 20, 2);
            $table->decimal('total_vat_payable_usd', 20, 2);
            $table->decimal('total_amount_due_with_penalties_tzs', 20, 2)->default(0);
            $table->decimal('total_amount_due_with_penalties_usd', 20, 2)->default(0);
            $table->decimal('penalty_tzs', 20, 2)->default(0);
            $table->decimal('penalty_usd', 20, 2)->default(0);
            $table->decimal('interest_tzs', 20, 2)->default(0);
            $table->decimal('interest_usd', 20, 2)->default(0);
            $table->decimal('infrastructure', 20, 2);
            $table->enum('currency',['TZS', 'USD', 'BOTH'])->default('TZS');
            $table->decimal('infrastructure_znz_znz', 20, 2);
            $table->decimal('infrastructure_znz_tm', 20, 2);
            $table->dateTime('submitted_at')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->dateTime('filing_due_date')->nullable();
            $table->dateTime('payment_due_date')->nullable();
            $table->enum('status', ReturnStatus::getConstants());
            $table->enum('application_status', DisputeStatus::getConstants());
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
        Schema::dropIfExists('port_returns');
    }
}
