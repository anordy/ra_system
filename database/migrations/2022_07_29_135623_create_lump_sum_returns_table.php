<?php

use App\Enum\DisputeStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLumpSumReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lump_sum_returns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('filed_by_id');
            $table->string('filed_by_type');
            $table->unsignedBigInteger('tax_type_id');
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('business_location_id');
            $table->unsignedBigInteger('financial_month_id');
            $table->unsignedBigInteger('financial_year_id');
            $table->decimal('total_amount_due', 40, 2)->default(0);
            $table->decimal('total_amount_due_with_penalties', 40, 2)->default(0);
            $table->integer('quarter');
            $table->string('quarter_name')->nullable();
            $table->enum('currency',['TZS', 'USD', 'BOTH'])->default('TZS');
            $table->integer('amount')->default(0);
            $table->integer('edited_count')->default(0);
            $table->bigInteger('control_no')->nullable();
            $table->decimal('penalty', 20, 2)->default(0);
            $table->decimal('interest', 20, 2)->default(0);
            $table->dateTime('filing_due_date')->nullable();
            $table->dateTime('payment_due_date')->nullable();
            $table->dateTime('submitted_at')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->string('status');
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
        Schema::dropIfExists('lump_sum_returns');
    }
}
