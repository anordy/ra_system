<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_refunds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('port_id');
            $table->unsignedBigInteger('business_location_id')->nullable();
            $table->decimal('total_exclusive_tax_amount', 20, 2);
            $table->decimal('total_payable_amount', 20, 2);
            $table->decimal('rate');
            $table->string('importer_name')->nullable();
            $table->string('receipt_number')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('ztn_number')->nullable();
            $table->string('currency');
            $table->string('payment_status');
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('payment_due_date');
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
        Schema::dropIfExists('tax_refunds');
    }
}
