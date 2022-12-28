<?php

use App\Enum\ReturnApplicationStatus;
use App\Enum\ReturnCategory;
use App\Models\Returns\ReturnStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmTransactionReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('em_transaction_returns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_location_id');
            $table->unsignedBigInteger('business_id');
            $table->string('filed_by_type');
            $table->unsignedBigInteger('filed_by_id');
            $table->unsignedBigInteger('tax_type_id');
            $table->unsignedBigInteger('financial_year_id');
            $table->integer('edited_count')->default(0);
            $table->enum('currency',['TZS', 'USD', 'BOTH'])->default('TZS');
            $table->string('financial_month_id');
            $table->decimal('total_amount_due',38, 2)->default(0);
            $table->decimal('total_amount_due_with_penalties',38, 2)->default(0);
            $table->decimal('penalty', 20, 2)->default(0);
            $table->decimal('interest', 20, 2)->default(0);
            $table->timestamp('filing_due_date')->nullable();
            $table->timestamp('payment_due_date')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->enum('status', ReturnStatus::getConstants());
            $table->enum('application_status', ReturnApplicationStatus::getConstants());
            $table->enum('return_category', ReturnCategory::getConstants())->default(ReturnCategory::NORMAL);
            $table->softDeletes();
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
        Schema::dropIfExists('em_transaction_returns');
    }
}
