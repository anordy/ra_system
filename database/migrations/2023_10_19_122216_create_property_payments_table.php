<?php

use App\Enum\BillStatus;
use App\Enum\PropertyPaymentCategoryStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertyPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id')->index('property_payment_id_index');
            $table->unsignedBigInteger('financial_year_id');
            $table->unsignedBigInteger('currency_id');
            $table->decimal('amount', 20, 2);
            $table->decimal('interest', 20, 2);
            $table->decimal('total_amount', 20, 2);
            $table->timestamp('payment_date');
            $table->timestamp('curr_payment_date');
            $table->enum('payment_status', BillStatus::getConstants());
            $table->enum('payment_category', PropertyPaymentCategoryStatus::getConstants());
            $table->timestamp('paid_at')->nullable();
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
        Schema::dropIfExists('property_payments');
    }
}
