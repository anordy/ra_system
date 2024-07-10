<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxpayerLedgerPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxpayer_ledger_payments', function (Blueprint $table) {
            $table->id();
            $table->string('ledger_ids', 100);
            $table->string('currency', 5);
            $table->decimal('total_amount', 20, 2);
            $table->unsignedBigInteger('taxpayer_id');
            $table->unsignedBigInteger('staff_id')->nullable();
            $table->string('status', 15)->default('pending');
            $table->string('marking', 255)->nullable();
            $table->integer('is_partial')->default(0);
            $table->timestamp('approved_on')->nullable();
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
        Schema::dropIfExists('taxpayer_ledger_payments');
    }
}
