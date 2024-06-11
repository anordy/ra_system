<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxpayerLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxpayer_ledgers', function (Blueprint $table) {
            $table->id();
            $table->string('source_type', 100);
            $table->unsignedBigInteger('source_id');
            $table->unsignedBigInteger('financial_month_id')->nullable();
            $table->unsignedBigInteger('zm_payment_id')->nullable();
            $table->unsignedBigInteger('business_id')->nullable();
            $table->unsignedBigInteger('business_location_id')->nullable();
            $table->unsignedBigInteger('taxpayer_id');
            $table->unsignedBigInteger('tax_type_id');
            $table->timestamp('transaction_date');
            $table->string('transaction_type', 10);
            $table->string('currency', 5);
            $table->string('description', 255)->nullable();
            $table->decimal('principal_amount', 20, 2);
            $table->decimal('interest_amount', 20, 2);
            $table->decimal('penalty_amount', 20, 2);
            $table->decimal('total_amount', 20, 2);
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
        Schema::dropIfExists('taxpayer_ledgers');
    }
}
