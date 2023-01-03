<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVatLocalPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vat_local_purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_location_id');
            $table->unsignedBigInteger('financial_month_id');
            $table->unsignedBigInteger('vat_return_id')->nullable();
            $table->string('supplier_ztn_number');
            $table->string('tax_invoice_number');
            $table->timestamp('date_of_tax_invoice');
            $table->string('release_number');
            $table->decimal('value',20,2);
            $table->decimal('vat',20,2);
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
        Schema::dropIfExists('vat_local_purchases');
    }
}
