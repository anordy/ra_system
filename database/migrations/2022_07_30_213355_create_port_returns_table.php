<?php

use App\Enum\ReturnApplicationStatus;
use App\Enum\ReturnCategory;
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
            $table->enum('currency', ['TZS', 'USD', 'BOTH'])->default('TZS');
            $table->integer('parent')->default(0);
            $table->unsignedBigInteger('tax_type_id');
            $table->unsignedBigInteger('financial_year_id');
            $table->integer('edited_count')->default(0);
            $table->enum('status', ReturnStatus::getConstants());
            $table->enum('application_status', ReturnApplicationStatus::getConstants());
            $table->enum('return_category', ReturnCategory::getConstants())->default(ReturnCategory::NORMAL);
            $table->decimal('infrastructure_tax', 20, 2)->nullable();
            $table->decimal('infrastructure_znz_znz', 20, 2);
            $table->decimal('infrastructure_znz_tm', 20, 2);
            $table->string('financial_month_id');
            $table->decimal('airport_safety_fee', 20, 2)->default(0);
            $table->decimal('airport_service_charge', 20, 2)->default(0);
            $table->decimal('seaport_service_charge', 20, 2)->default(0);
            $table->decimal('seaport_transport_charge', 20, 2)->default(0);
            $table->decimal('total_amount_due', 20, 2)->default(0);
            $table->decimal('total_amount_due_with_penalties', 20, 2)->default(0);
            $table->decimal('penalty', 20, 2)->default(0);
            $table->decimal('interest', 20, 2)->default(0);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('paid_at')->nullable();
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
        Schema::dropIfExists('port_returns');
    }
}
