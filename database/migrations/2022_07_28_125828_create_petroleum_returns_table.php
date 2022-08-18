<?php

use App\Enum\ReturnApplicationStatus;
use App\Enum\ReturnCategory;
use App\Models\Returns\ReturnStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePetroleumReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('petroleum_returns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('business_location_id');
            $table->string('filed_by_type');
            $table->unsignedBigInteger('filed_by_id');
            $table->unsignedBigInteger('tax_type_id');
            $table->unsignedBigInteger('financial_year_id');
            $table->unsignedBigInteger('financial_month_id');
            $table->enum('currency',['TZS', 'USD', 'BOTH'])->default('TZS');
            $table->decimal('total_amount_due', 40, 2)->default(0);
            $table->decimal('total_amount_due_with_penalties', 40,2)->default(0);
            $table->decimal('petroleum_levy', 40, 2)->default(0);
            $table->decimal('infrastructure_tax', 40, 2)->default(0);
            $table->decimal('rdf_tax', 40, 2)->default(0);
            $table->decimal('road_lincence_fee', 40, 2)->default(0);
            $table->enum('status', ReturnStatus::getConstants());
            $table->decimal('penalty', 40, 2)->default(0);
            $table->decimal('interest', 40, 2)->default(0);
            $table->dateTime('filing_due_date')->nullable();
            $table->dateTime('payment_due_date')->nullable();
            $table->dateTime('submitted_at')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->enum('application_status', ReturnApplicationStatus::getConstants());
            $table->enum('category', ReturnCategory::getConstants())->default(ReturnCategory::NORMAL);
            $table->integer('edited_count')->default(0);
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
        Schema::dropIfExists('petroleum_returns');
    }
}
