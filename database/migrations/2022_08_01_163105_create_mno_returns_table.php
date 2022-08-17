<?php

use App\Enum\ReturnApplicationStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMnoReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mno_returns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('business_location_id');
            $table->unsignedBigInteger('filed_by_id');
            $table->string('filed_by_type');
            $table->unsignedBigInteger('financial_year_id');
            $table->unsignedBigInteger('financial_month_id');
            $table->unsignedBigInteger('tax_type_id');
            $table->decimal('total_amount_due', 40, 2);
            $table->decimal('total_amount_due_with_penalties', 40, 2);
            $table->string('status');
            $table->enum('currency',['TZS', 'USD', 'BOTH'])->default('TZS');
            $table->decimal('penalty', 20,2);
            $table->decimal('interest',20,2);
            $table->dateTime('filing_due_date')->nullable();
            $table->dateTime('payment_due_date')->nullable();
            $table->dateTime('submitted_at')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->enum('application_status', ReturnApplicationStatus::getConstants());
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mno_returns');
    }
}
